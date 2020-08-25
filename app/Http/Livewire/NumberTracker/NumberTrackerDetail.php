<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use Livewire\Component;

class NumberTrackerDetail extends Component
{

    public $period = 'd';

    public $numbersTracked;

    public $dateSelected;

    public function mount()
    {
        $this->getTrackerNumbers();
    }

    public function render()
    {
        $showOptions = [
            'Daily Total', 
            'Weekly Total',
            'Monthly Total',
            'Statistics',
        ];
        return view('livewire.number-tracker.number-tracker-detail',compact('showOptions'));
    }

    public function setPeriod($p)
    {
        $this->period = $p;
        $this->getTrackerNumbers();
    }

    public function getTrackerNumbers()
    {
        $this->numbersTracked = DailyNumber::query()
            ->leftJoin('users', function($join) {
                $join->on('users.id', '=', 'daily_numbers.id');
            })
            ->whereDate('date', $dateSelected)
            ->orderBy('doors', 'desc')
            ->take(5)
            ->get();                         
    }
}
