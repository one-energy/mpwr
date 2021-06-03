<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use App\Models\Region;
use Illuminate\Support\Collection;
use Livewire\Component;

class RegionRow extends Component
{
    public Region $region;

    public Collection $offices;

    public Collection $selectedOfficesId;

    public Collection $selectedUsersId;

    public bool $itsSelected = false;

    public bool $itsOpen = false;

    public int $quantityOfficesSelected = 0;

    public string $sortBy = 'hours_worked';

    public string $sortDirection = 'asc';

    protected $listeners = [
        'toggleOffice',
        'sorted' => 'sortOffices',
    ];

    public function mount()
    {
        $this->sortOffices('hours_worked', 'asc');

        $this->selectedUsersId   = collect();
        $this->selectedOfficesId = collect();
    }

    public function render()
    {
        return view('livewire.number-tracker.region-row');
    }

    public function sumOf($property)
    {
        $sum = $this->region->offices->sum(function ($office) use ($property) {
            return $office->dailyNumbers->sum($property);
        });

        return $sum > 0 ? $sum : html_entity_decode('&#8212;');
    }

    public function collapseRegion()
    {
        $this->itsOpen = !$this->itsOpen;
    }

    public function selectRegion()
    {
        if ($this->itsSelected) {
            $this->selectedUsersId   = $this->selectedUsersId->merge($this->getUserIds());
            $this->selectedOfficesId = $this->selectedOfficesId->merge($this->region->offices->map->id);
        } else {
            $this->selectedUsersId   = collect([]);
            $this->selectedOfficesId = collect([]);
        }
        $this->emit('regionSelected', $this->region->id, $this->itsSelected);
    }

    public function anyOfficeSelected()
    {
        $this->itsSelected = $this->selectedOfficesId->isNotEmpty();
    }

    public function toggleOffice(Office $office, bool $insert)
    {
        if ($insert) {
            $this->selectedOfficesId->push($office->id);
        } else {
            $this->selectedOfficesId = $this->selectedOfficesId->filter(fn($officeId) => $officeId !== $office->id);
        }

        $this->anyOfficeSelected();
    }

    private function getUserIds()
    {
        return $this->region->offices->map(function ($office) {
            return $office->dailyNumbers->unique('user_id')->map(function ($dailyNumber) {
                return $dailyNumber->user_id;
            })->filter();
        })->flatten();
    }

    public function sortOffices($sortBy, $sortDirection)
    {
        $this->offices = $sortDirection === 'asc'
            ? $this->sortOfficesAsc($this->region->offices, $sortBy)
            : $this->sortOfficesDesc($this->region->offices, $sortBy);
    }

    private function sortOfficesAsc(Collection $offices, string $sortBy)
    {
        return $offices->sortBy(
            fn (Office $office) => $office->dailyNumbers->count() ? $office->dailyNumbers->sum($sortBy) : 0
        )->values();
    }

    private function sortOfficesDesc(Collection $offices, string $sortBy)
    {
        return $offices->sortByDesc(
            fn (Office $office) => $office->dailyNumbers->count() ? $office->dailyNumbers->sum($sortBy) : 0
        )->values();
    }
}
