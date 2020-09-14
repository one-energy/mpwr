<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NumberTrackerDetail extends Component
{
    public $period = 'd';

    public $numbersTracked = [];

    public $offices = [];

    public $regions = [];

    public $users = [];

    public $userSearch = "";
    
    public $numbersTrackedLast = [];

    public $date;

    public $graficValue;

    public $graficValueLast;

    public $filterBy = "doors";

    public $dateSelected;

    public $activeFilters = [];

    public function mount()
    {
        $this->dateSelected = date('Y-m-d', time());
        $this->getOffices();
        $this->getRegions();
        $this->getUsers();
    }

    public function render()
    {
        $this->numbersTracked = $this->getTrackerNumbers();
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

    public function updateSearch()
    {
        $this->users = User::where(DB::raw("CONCAT(`first_name`, ' ',  `last_name`)"), 'like', "%" . $this->userSearch . "%")
            ->orWhere('email', 'like', "%{$this->userSearch}%")->get();
    }

    public function getTrackerNumbers()
    {
        $query = DailyNumber::query()
            ->leftJoin('users', function ($join) {
                $join->on('users.id', '=', 'daily_numbers.user_id');
            })
            ->join('offices', 'users.office_id', '=', 'offices.id')
            ->select([DB::raw("users.first_name, users.last_name, daily_numbers.id, daily_numbers.user_id, SUM(doors) as doors,  
                    SUM(hours) as hours,  SUM(sets) as sets,  SUM(sits) as sits,  SUM(set_closes) as set_closes, SUM(closes) as closes")]);

        $queryLast = DailyNumber::query()
            ->leftJoin('users', function ($join) {
                $join->on('users.id', '=', 'daily_numbers.user_id');
            })
            ->select([DB::raw("users.first_name, users.last_name, daily_numbers.id, daily_numbers.user_id, 
                    SUM(doors) as doors,  SUM(hours) as hours,  SUM(sets) as sets,  SUM(sits) as sits,  SUM(set_closes) as set_closes, SUM(closes) as closes")]);

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

        if (count($this->activeFilters) > 0) {
            $activeFilters = $this->activeFilters;
            $query->where(function ($query) use ($activeFilters) {
                foreach ($activeFilters as $key => $filter) {
                    $id = $filter['id'];
                    if ($filter['type'] == "user") {
                        $query->orWhere('daily_numbers.user_id', '=', $id);
                    }
                    if ($filter['type'] == "office") {
                        $query->orWhere('office_id', '=', $id);
                    }
                    if ($filter['type'] == "region") {
                        $query->orWhere('region_id', '=', $id);
                    }
                }
            });
        }

        $this->numbersTrackedLast = $queryLast->groupBy('user_id')
            ->get();

        $this->graficValueLast    = $this->numbersTrackedLast->sum($this->filterBy);

        return $query->groupBy('daily_numbers.user_id')
            ->orderBy($this->filterBy, 'desc')
            ->get();
    }

    public function addFilter($data, $type)
    {
        if ($type == 'user') {
            $element = [
                'type'       => $type,
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'id'         => $data['id'],
            ];
            if (!in_array($element, $this->activeFilters)) {
                array_push($this->activeFilters, $element);
            }
        } else {
            $element = [
                'type' => $type,
                'name' => $data['name'],
                'id'   => $data['id'],
            ];
            if (!in_array($element, $this->activeFilters)) {
                array_push($this->activeFilters, $element);
            }
        }
    }

    public function removeFilter($item)
    {
        unset($this->activeFilters[$item]);
    }

    public function getOffices()
    {
        $this->offices = Office::all();
    }

    public function getRegions()
    {
        $this->regions = Region::all();
    }

    public function getUsers()
    {
        $this->users = User::all();
    }
}
