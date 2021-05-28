<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Region;
use Livewire\Component;

class RegionRow extends Component
{
    public Region $region;

    public bool $itsSelected = false;

    public bool $itsOpen = false;

    public int $quantintyOfficesSelected = 0;

    public function render()
    {
        return view('livewire.number-tracker.region-row');
    }

    public function sumOf($property)
    {
        $sum = $this->region->offices->sum(function ($office) use ($property) {
            return $office->dailyNumbers->sum($property);
        });

        return $sum > 0 ? $sum :  html_entity_decode('&#8212;');
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
        $this->quantintyOfficesSelected++;
        if ($this->isAllOfficesSelecteds()) {
            $this->itsSelected == true;
        }
        if ($this->quantintyOfficesSelected == 0) {
            $this->itsSelected == false;
        }
    }

    public function isAllOfficesSelecteds()
    {
        return ($this->region->offices->count() == $this->quantintyOfficesSelected) && $this->quantintyOfficesSelected > 0;
    }

}
