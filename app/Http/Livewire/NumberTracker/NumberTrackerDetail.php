<?php

namespace App\Http\Livewire\NumberTracker;

use Livewire\Component;

class NumberTrackerDetail extends Component
{

    public function mount()
    {
      
    }

    public function render()
    {
        $showOptions = [
            'Daily Total', 
            'Weekly Total',
            'Monthly Total',
            'Statistics',
        ];
        $trackingInformation = [
            ['region_member' => 'Donna Walker',    'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Chris Wiliams',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Ana Hendersen',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Donald Barnes',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Joe Richardson',  'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Tammy Collins',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Joseph Bennett',  'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Michelle Powell', 'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Jerry Kelly',     'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Donna Walker',    'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
        ];
        return view('livewire.number-tracker.number-tracker-detail',compact('showOptions', 'trackingInformation'));
    }
}
