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

    public $top10Sets;

    public $top10SetCloses;

    public $userId;

    public $user;

    public $totalDoors = 0;

    public $totalHours = 0;

    public $totalSets = 0;

    public $totalSits = 0;

    public $totalCloses = 0;

    public $dpsRatio;

    public $hpsRatio;

    public $sitRatio;

    public $closeRatio;

    public $photoUrl;
    
    public $firstName;

    public $lastName;

    public $office_id;

    public $hoursPeriod = 'daily';

    public $setsPeriod = 'daily';

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

        $this->photoUrl  = $this->user->photo_url;
        $this->firstName = $this->user->first_name;
        $this->lastName  = $this->user->last_name;
        $this->office_id = $this->user->office_id;

        $query = $this->user->dailyNumbers;

        $this->totalDoors  = $query->sum('doors');
        $this->totalHours  = $query->sum('hours');
        $this->totalSets   = $query->sum('sets');
        $this->totalSits   = $query->sum('sits');
        $this->totalCloses = $query->sum('set_closes');

        if ($this->totalSets) {
            $this->dpsRatio   = ($this->totalDoors / $this->totalSets);
            $this->hpsRatio   = ($this->totalHours / $this->totalSets);
            $this->sitRatio   = ($this->totalSits / $this->totalSets);
        } else {
            $this->dpsRatio   = 0;
            $this->hpsRatio   = 0;
            $this->sitRatio   = 0;
        }

        if ($this->totalSits) {
            $this->closeRatio = ($this->totalCloses / $this->totalSits);
        } else {
            $this->closeRatio = 0;
        }
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
            ->orderByDesc('hours')
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
            ->orderByDesc('sets')
            ->take(10)
            ->get();
    }

    public function setTop10SetClosesPeriod($closesPeriod)
    {
        $this->closesPeriod = $closesPeriod;

        $query = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            });

        if ($this->closesPeriod === "daily") {
            $query
                ->whereDate('daily_numbers.created_at', Carbon::today());
        } elseif ($this->closesPeriod === "weekly") {
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
            ->orderByDesc('set_closes')
            ->take(10)
            ->get();
    }

    public function render()
    {
        $this->setTop10HoursPeriod($this->hoursPeriod);
        $this->setTop10SetsPeriod($this->setsPeriod);
        $this->setTop10SetClosesPeriod($this->closesPeriod);

        return view('livewire.scoreboard');
    }
}