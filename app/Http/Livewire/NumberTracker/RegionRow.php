<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use App\Models\Region;
use Illuminate\Support\Collection;
use Livewire\Component;

class RegionRow extends Component
{
    public Region $region;

    public Collection $selectedOfficesId;

    public Collection $selectedUsersId;

    public bool $itsSelected = false;

    public bool $itsOpen = false;

    public int $quantityOfficesSelected = 0;

    protected $listeners = [
        'toggleOffice',
    ];

    public function mount()
    {
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

    public function getOfficesProperty()
    {
        return $this->region->offices;
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
            $this->selectedOfficesId = $this->selectedOfficesId->filter(fn ($officeId) => $officeId !== $office->id);
        }

        $this->anyOfficeSelected();
    }
}
