<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class OfficeRow extends Component
{
    public int $officeId;

    public ?Office $office;

    public string $period;

    public string $selectedDate;

    public bool $withTrashed;

    public Collection $dailyNumbers;

    public Collection $selectedUsers;

    public bool $itsOpen = false;

    public bool $selected = false;

    public bool $selectedTotal = false;

    public string $sortBy = 'hours_worked';

    public string $sortDirection = 'asc';

    public $listeners = [
        'regionSelected',
        'toggleUser',
        'setDateOrPeriod',
        'sorted' => 'sortDailyNumbers',
    ];

    public function mount()
    {
        Cache::forget(sprintf('user-%s-office-%s-trashed-%s', user()->id, $this->officeId, 1));
        Cache::forget(sprintf('user-%s-office-%s-trashed-%s', user()->id, $this->officeId, 0));

        $this->office        = $this->findOffice($this->officeId);
        $this->selectedTotal = $this->selected;

        $usersIds            = $this->getUniqueUsersIds();
        $this->selectedUsers = $this->selectedUsers->filter(
            fn($userId) => in_array($userId, $usersIds->toArray(), true)
        );

        $this->sortDailyNumbers($this->sortBy, $this->sortDirection);
    }

    public function render()
    {
        return view('livewire.number-tracker.office-row');
    }

    public function hydrateOffice()
    {
        $this->office = $this->findOffice($this->officeId);
    }

    public function collapseOffice()
    {
        $this->itsOpen = !$this->itsOpen;

        if ($this->itsOpen) {
            $this->sortDailyNumbers($this->sortBy, $this->sortDirection);
        }
    }

    public function selectOffice()
    {
        if ($this->selected) {
            $this->selectedUsers = $this->selectedUsers->merge($this->getUniqueUsersIds());
        } else {
            $this->selectedUsers = collect();
        }

        $this->emitUp('toggleOffice', $this->office->id, $this->selected);
        $this->emit('officeSelected', $this->office->id, $this->selected);
        $this->isAnyUserSelected();
    }

    public function selectTotal()
    {
        $this->selected = $this->selectedTotal;
        $this->selectOffice();
    }

    public function regionSelected(int $regionId, bool $selected, Collection $selectedUsersIds)
    {
        if ($this->office->region_id === $regionId) {
            $this->selected      = $selected;
            $usersIds            = $this->getUniqueUsersIds();
            $this->selectedUsers = $selectedUsersIds->filter(
                fn($userId) => in_array($userId, $usersIds->toArray(), true)
            );
            $this->emit('officeSelected', $this->office->id, $selected);
        }
    }

    public function sumBy($field)
    {
        return $this->parseNumber(
            $this->office->dailyNumbers->sum(fn($dailyNumber) => $dailyNumber[$field])
        );
    }

    public function parseNumber($value)
    {
        return $value > 0 ? $value : html_entity_decode('&#8212;');
    }

    public function toggleUser(int $userId, bool $isSelected)
    {
        if ($isSelected) {
            $this->selectedUsers->push($userId);
        } else {
            $this->selectedUsers = $this->selectedUsers->filter(function ($selectedUser) use ($userId) {
                return $selectedUser !== $userId;
            });
        }

        $this->isAnyUserSelected();
    }

    public function isAnyUserSelected()
    {
        $this->selected = $this->selectedUsers->isNotEmpty();

        if (!$this->selected) {
            $this->emitUp('toggleOffice', $this->office->id, $this->selected);
            $this->emit('officeSelected', $this->office->id, $this->selected);
        }

        $this->selectedTotal = $this->selectedUsers->count() === $this->office->dailyNumbers->unique('user_id')->count();
    }

    public function sortDailyNumbers($sortBy, $sortDirection)
    {
        $this->sortBy        = $sortBy;
        $this->sortDirection = $sortDirection;

        $this->getDailyNumbers();
    }

    public function setDateOrPeriod($date, $period)
    {
        $this->selectedDate = $date;
        $this->period       = $period;
        $this->office       = $this->findOffice($this->officeId);

        $this->getDailyNumbers();
    }

    public function getDailyNumbers()
    {
        $office       = $this->office ?? $this->findOffice($this->officeId);
        $groupedUsers = $office->dailyNumbers->groupBy('user_id')->collect();

        $this->dailyNumbers = $this->sortDirection === 'asc'
            ? $this->sortUsersAsc($groupedUsers, $this->sortBy)
            : $this->sortUsersDesc($groupedUsers, $this->sortBy);
    }

    private function getCacheKey()
    {
        $trashed = $this->withTrashed ? 1 : 0;

        return sprintf('user-%s-office-%s-trashed-%s', user()->id, $this->officeId, $trashed);
    }

    private function sortUsersAsc(Collection $dailyNumbers, string $sortBy)
    {
        return $dailyNumbers->sortBy(
            fn(Collection $trackers) => $trackers->sum($sortBy)
        )->values();
    }

    private function sortUsersDesc(Collection $dailyNumbers, string $sortBy)
    {
        return $dailyNumbers->sortByDesc(
            fn(Collection $trackers) => $trackers->sum($sortBy)
        )->values();
    }

    private function findOffice(int $officeId)
    {
        $office = Cache::rememberForever($this->getCacheKey(), function () use ($officeId) {
            return Office::query()
                ->when($this->withTrashed, fn($query) => $query->withTrashed())
                ->find($officeId);
        });

        return $office->load($this->getEagerLoadingRelation());
    }

    private function getEagerLoadingRelation(): array
    {
        return [
            'dailyNumbers' => function ($query) {
                $query
                    ->when($this->withTrashed, fn($query) => $query->withTrashed())
                    ->when(!$this->withTrashed, fn($query) => $query->has('user'))
                    ->inPeriod($this->period, new Carbon($this->selectedDate));
            },
        ];
    }

    private function getUniqueUsersIds(): Collection
    {
        return $this->office->dailyNumbers->unique('user_id')->pluck('user_id');
    }
}
