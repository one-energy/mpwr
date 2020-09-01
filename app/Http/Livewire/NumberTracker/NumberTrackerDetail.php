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

    public $numbersTrackedLast = [];

    public $date;

    public $graficValue;

    public $graficValueLast;

    public $filterBy = "doors";

    public $dateSelected;

    public function mount()
    {
        $this->dateSelected = date('Y-m-d', time());
    }

    public function render()
    {
        $this->numbersTracked =  $this->getTrackerNumbers();
        $this->graficValue    = $this->numbersTracked->sum($this->filterBy);
        $showOptions          = [
            'Daily Total',
            'Weekly Total',
            'Monthly Total',
            'Statistics',
        ];
        return view('livewire.number-tracker.number-tracker-detail', ['showOptions' => $showOptions]);
    }

    public function setPeriod($p)
    {
        $this->period = $p;
    }

    public function setFilterBy($filter)
    {
        $this->filterBy = $filter;
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

        $queryLast = DailyNumber::query()
            ->leftJoin('users', function ($join) {
                $join->on('users.id', '=', 'daily_numbers.user_id');
            })
            ->select([DB::raw("users.first_name, users.last_name, daily_numbers.id, daily_numbers.user_id, SUM(doors) as doors,  SUM(hours) as hours,  SUM(sets) as sets,  SUM(sits) as sits,  SUM(set_closes) as set_closes, SUM(closes) as closes")]);;

        if ($this->period == 'd') {
            $query->whereDate('date', $this->dateSelected);
            $queryLast->whereDate('date', date('Y-m-d', strtotime($this->dateSelected . '-1 day')));
        } elseif ($this->period == 'w') {
            $query->whereBetween('date', [Carbon::createFromFormat('Y-m-d', $this->dateSelected)->startOfWeek(), Carbon::createFromFormat('Y-m-d', $this->dateSelected)->endOfWeek()]);
            $queryLast->whereBetween('date', [Carbon::createFromFormat('Y-m-d', $this->dateSelected)->subWeek()->startOfWeek(), Carbon::createFromFormat('Y-m-d', $this->dateSelected)->subWeek()->endOfWeek()]);
        } else {
            $query->whereMonth('date', '=', Carbon::createFromFormat('Y-m-d', $this->dateSelected)->month);
            $queryLast->whereMonth('date', '=', Carbon::createFromFormat('Y-m-d', $this->dateSelected)->subMonth()->month);
        }

        $this->numbersTrackedLast = $queryLast->groupBy('user_id')
            ->get();

        $this->graficValueLast    = $this->numbersTrackedLast->sum($this->filterBy);
        return $query->groupBy('user_id')
            ->orderBy($this->filterBy, 'desc')
            ->get();
    }
}
