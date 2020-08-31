<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NumberTrackerDetail extends Component
{

    public $period = 'd';

    public $numbersTracked = [];

    public $date;

    public $filterBy = "doors";

    public $dateSelected;

    public $activeFilters = ['Regions', 'Offices', 'Users', 'Admin', 'DevSquad'];

    public function mount()
    {
        $this->dateSelected = date('Y-m-d', time());
        
    }

    public function render()
    {
        $this->numbersTracked = $this->getTrackerNumbers();
        $showOptions          = [
            'Daily Total', 
            'Weekly Total',
            'Monthly Total',
            'Statistics',
        ];
        return view('livewire.number-tracker.number-tracker-detail',['showOptions' => $showOptions]);
    }

    public function setPeriod($p)
    {
        $this->period = $p;
       
    }

    public function setDate()
    {
        $this->dateSelected = date('Y-m-d', strtotime($this->date));
    }

    public function getTrackerNumbers()
    {
        $query = DailyNumber::query()
        ->leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'daily_numbers.user_id');
        })
        ->select([DB::raw("users.first_name, users.last_name, daily_numbers.id, daily_numbers.user_id, SUM(doors) as doors,  SUM(hours) as hours,  SUM(sets) as sets,  SUM(sits) as sits,  SUM(set_closes) as set_closes, SUM(closes) as closes")]);

        if ($this->period == 'd') {
            $query->whereDate('date', $this->dateSelected);
        } elseif ($this->period == 'w') {
            $query->whereBetween('date', [Carbon::createFromFormat('Y-m-d', $this->dateSelected)->startOfWeek(), Carbon::createFromFormat('Y-m-d', $this->dateSelected)->endOfWeek()]);
        } else {
            $query->whereMonth('date', '=', Carbon::createFromFormat('Y-m-d', $this->dateSelected)->month);
        }

        return $query->groupBy('user_id')
            ->orderBy($this->filterBy, 'desc')
            ->take(5)
            ->get();
        
    }
}
