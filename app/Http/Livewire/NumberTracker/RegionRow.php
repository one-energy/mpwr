<?php

namespace App\Http\Livewire\NumberTracker;

use Livewire\Component;

class RegionRow extends Component
{
    public $region;

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.number-tracker.region-row');
    }

    public function sumRegionNumberTracker($region, $field, $filterBySelected = false)
    {
        $sum    = 0;
        $region = (object)$region;
        if ($region->selected || !$filterBySelected) {
            $collectOffice = $this->getCollectionOf($region->sortedOffices);
            $sum           = $collectOffice->sum(function ($office) use ($filterBySelected, $field) {
                return $this->sumOfficeNumberTracker($office, $field, $filterBySelected);
            });
        }

        return $sum;
    }

}
