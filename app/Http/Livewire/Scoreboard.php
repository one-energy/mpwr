<?php

namespace App\Http\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Scoreboard extends Component
{
    public $filterTypes;

    public $top10Hours;

    public $top10Doors;

    public $top10Sets;

    public $top10SetCloses;

    public $userId;

    public $user;

    public $userArray = [];

    public $dpsRatio;

    public $hpsRatio;

    public $sitRatio;

    public $closeRatio;

    public $hoursPeriod = 'daily';

    public $doorsPeriod = 'daily';

    public $setsPeriod = 'daily';

    public $setClosesPeriod = 'daily';

    public $closesPeriod = 'daily';

    public function mount()
    {
        $this->filterTypes = [
            ['index' => 'leaderboards',   'value' => 'Leaderboards'],
            ['index' => 'records',        'value' => 'Records'],
        ];
    }

    public function setUser($userId)
    {
        $this->userId = $userId;
        $this->user   = User::find($userId);

        $query = $this->user->dailyNumbers;

        $this->userArray             = [
            'photo_url'     => $this->user->photo_url,
            'first_name'    => $this->user->first_name,
            'last_name'     => $this->user->last_name,
            'office_name'   => $this->user->office->name,
            'totalDoors'    => $query->sum('doors'),
            'totalHours'    => $query->sum('hours'),
            'totalSets'     => $query->sum('sets'),
            'totalSits'     => $query->sum('sits'),
            'totalCloses'   => $query->sum('set_closes'),
        ];
        // $this->photoUrl   = $this->user->photo_url;
        // $this->firstName  = $this->user->first_name;
        // $this->lastName   = $this->user->last_name;
        // $this->officeName = $this->user->office->name;

        // $this->totalDoors  = $query->sum('doors');
        // $this->totalHours  = $query->sum('hours');
        // $this->totalSets   = $query->sum('sets');
        // $this->totalSits   = $query->sum('sits');
        // $this->totalCloses = $query->sum('set_closes');

        if ($query->sum('sets') > 0) {
            $this->dpsRatio   = ($query->sum('doors') / $query->sum('sets'));
            $this->hpsRatio   = ($query->sum('hours') / $query->sum('sets'));
            $this->sitRatio   = ($query->sum('sits') / $query->sum('sets'));
        } else {
            $this->dpsRatio   = 0;
            $this->hpsRatio   = 0;
            $this->sitRatio   = 0;
        }

        if ($query->sum('sits') > 0) {
            $this->closeRatio = ($query->sum('set_closes') / $query->sum('sits'));
        } else {
            $this->closeRatio = 0;
        }

        $this->dispatchBrowserEvent('setUserNumbers', [
            'doors'      => $query->sum('doors'),
            'hours'      => $query->sum('hours'),
            'sets'       => $query->sum('sets'),
            'sits'       => $query->sum('sits'),
            'set_closes' => $query->sum('set_closes'),
        ]);
    }

    public function setTop10HoursPeriod($hoursPeriod)
    {
        $this->hoursPeriod = $hoursPeriod;

        $query = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            });

        if ($this->hoursPeriod === "daily") {
            $query
                ->whereDate('daily_numbers.created_at', Carbon::today());
        } elseif ($this->hoursPeriod === "weekly") {
            $query
                ->whereBetween('daily_numbers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } else {
            $query
                ->whereMonth('daily_numbers.created_at', '=', Carbon::now()->month);
        }

        $this->top10Hours = $query
            ->select(DB::raw('sum(daily_numbers.hours) as hours, users.office_id, users.first_name, users.last_name, users.id'))
            ->groupBy('users.id')
            ->whereNotNull('daily_numbers.hours')
            ->whereDepartmentId(user()->department_id)
            ->orderByDesc('hours')
            ->take(10)
            ->get();
    }

    public function setTop10DoorsPeriod($doorsPeriod)
    {
        $this->doorsPeriod = $doorsPeriod;

        $query = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            });

        if ($this->doorsPeriod === "daily") {
            $query
                ->whereDate('daily_numbers.created_at', Carbon::today());
        }
        if ($this->doorsPeriod === "weekly") {
            $query
                ->whereBetween('daily_numbers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }
        if ($this->doorsPeriod === "monthly") {
            $query
                ->whereMonth('daily_numbers.created_at', '=', Carbon::now()->month);
        }

        $this->top10Doors = $query
            ->select(DB::raw('sum(daily_numbers.doors) as doors, users.office_id, users.first_name, users.last_name, users.id'))
            ->groupBy('users.id')
            ->whereNotNull('daily_numbers.doors')
            ->whereDepartmentId(user()->department_id)
            ->orderByDesc('doors')
            ->take(10)
            ->get();
    }

    public function setTop10SetsPeriod($setsPeriod)
    {
        $this->setsPeriod = $setsPeriod;

        $query = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            });

        if ($this->setsPeriod === "daily") {
            $query
                ->whereDate('daily_numbers.created_at', Carbon::today());
        } elseif ($this->setsPeriod === "weekly") {
            $query
                ->whereBetween('daily_numbers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } else {
            $query
                ->whereMonth('daily_numbers.created_at', '=', Carbon::now()->month);
        }

        $this->top10Sets = $query
            ->select(DB::raw('sum(daily_numbers.sets) as sets, users.office_id, users.first_name, users.last_name, users.id'))
            ->groupBy('users.id')
            ->whereNotNull('daily_numbers.sets')
            ->whereDepartmentId(user()->department_id)
            ->orderByDesc('sets')
            ->take(10)
            ->get();
    }

    public function setTop10SetClosesPeriod($setClosesPeriod)
    {
        $this->setClosesPeriod = $setClosesPeriod;

        $query = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            });

        if ($this->setClosesPeriod === "daily") {
            $query
                ->whereDate('daily_numbers.created_at', Carbon::today());
        } elseif ($this->setClosesPeriod === "weekly") {
            $query
                ->whereBetween('daily_numbers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } else {
            $query
                ->whereMonth('daily_numbers.created_at', '=', Carbon::now()->month);
        }

        $this->top10SetCloses = $query
            ->select(DB::raw('sum(daily_numbers.set_closes) as set_closes, users.office_id, users.first_name, users.last_name, users.id'))
            ->groupBy('users.id')
            ->whereNotNull('daily_numbers.set_closes')
            ->whereDepartmentId(user()->department_id)
            ->orderByDesc('set_closes')
            ->take(10)
            ->get();
    }

    public function setTop10ClosesPeriod($closesPeriod)
    {
        $this->closesPeriod = $closesPeriod;

        $query = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            });

        if ($this->closesPeriod === "daily") {
            $query->whereDate('daily_numbers.created_at', Carbon::today());
        } elseif ($this->closesPeriod === "weekly") {
            $query->whereBetween('daily_numbers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } else {
            $query->whereMonth('daily_numbers.created_at', '=', Carbon::now()->month);
        }

        $this->top10SetCloses = $query
            ->select(DB::raw('sum(daily_numbers.closes) as closes, users.office_id, users.first_name, users.last_name, users.id'))
            ->groupBy('users.id')
            ->whereNotNull('daily_numbers.closes')
            ->whereDepartmentId(user()->department_id)
            ->orderByDesc('closes')
            ->take(10)
            ->get();
    }

    public function render()
    {
        $this->setTop10HoursPeriod($this->hoursPeriod);
        $this->setTop10DoorsPeriod($this->hoursPeriod);
        $this->setTop10SetsPeriod($this->setsPeriod);
        $this->setTop10SetClosesPeriod($this->closesPeriod);

        return view('livewire.scoreboard');
    }
}
