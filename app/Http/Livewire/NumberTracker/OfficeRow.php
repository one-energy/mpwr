<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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

    public $listeners = [
        'regionSelected',
        'toggleUser',
        'setDateOrPeriod',
        'sorted' => 'sortDailyNumbers',
    ];

    public function mount()
    {
        $this->office = null;

        $this->sortDailyNumbers('hours_worked', 'asc');

        $this->selectedTotal = $this->selected;
        $this->selectedUsers = collect();
    }

    public function render()
    {
        $this->office = $this->findOffice($this->officeId);

        return view('livewire.number-tracker.office-row');
    }

    public function collapseOffice()
    {
        $this->itsOpen = !$this->itsOpen;
    }

    public function selectOffice()
    {
        if ($this->selected) {
            $this->selectedUsers = $this->selectedUsers->merge(
                $this->office->dailyNumbers->unique('user_id')->pluck('user_id'));
        } else {
            $this->selectedUsers = $this->selectedUsers->empty();
        }

        $this->emitUp('toggleOffice', $this->office, $this->selected);
        $this->emit('officeSelected', $this->office->id, $this->selected);
        $this->isAnyUserSelected();
    }

    public function selectTotal()
    {
        $this->selected = $this->selectedTotal;
        $this->selectOffice();
    }

    public function regionSelected(int $regionId, bool $selected)
    {
        if ($this->office->region_id === $regionId) {
            $this->selected = $selected;
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

    public function getUsersDailyNumbersProperty()
    {
        return $this->office->dailyNumbers->groupBy('user_id');
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
            $this->emitUp('toggleOffice', $this->office, $this->selected);
            $this->emit('officeSelected', $this->office->id, $this->selected);
        }

        $this->selectedTotal = $this->selectedUsers->count() === $this->office->dailyNumbers->unique('user_id')->count();
    }

    public function sortDailyNumbers($sortBy, $sortDirection)
    {
        $office = $this->office === null ? $this->findOffice($this->officeId) : $this->office;

        $groupedUsers = $office->dailyNumbers->groupBy('user_id')->collect();

        $this->dailyNumbers = $sortDirection === 'asc'
            ? $this->sortUsersAsc($groupedUsers, $sortBy)
            : $this->sortUsersDesc($groupedUsers, $sortBy);
    }

    public function setDateOrPeriod($date, $period)
    {
        $this->selectedDate = $date;
        $this->period       = $period;
        $this->office       = $this->findOffice($this->officeId);
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
        return Office::query()
            ->find($officeId)
            ->load([
                'dailyNumbers' => function ($query) {
                    $query
                        ->when($this->withTrashed, fn($query) => $query->withTrashed())
                        ->when(!$this->withTrashed, fn($query) => $query->has('user'))
                        ->inPeriod($this->period, new Carbon($this->selectedDate));
                },
            ]);
    }
}
