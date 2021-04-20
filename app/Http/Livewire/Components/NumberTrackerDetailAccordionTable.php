<?php

namespace App\Http\Livewire\Components;

use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Collection;
use Livewire\Component;

class NumberTrackerDetailAccordionTable extends Component
{
    use FullTable;

    public Collection $regions;

    public function render()
    {
        $this->regions = Region::all();
        return view('livewire.components.number-tracker-detail-accordion-table');
    }

    public function sortBy()
    {
        return 'doors';
    }
}
