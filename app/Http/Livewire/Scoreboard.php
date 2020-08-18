<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    public $first_name;

    public $last_name;

    public $office;

    // public function mount()
    // {
    //     $this->filterTypes = [
    //         ['index' => 'leaderboards',   'value' => 'Leaderboards'],
    //         ['index' => 'records',        'value' => 'Records'],
    //     ];

    //     $this->top10Hours = User::query()
    //         ->leftJoin('daily_numbers', function($join) {
    //             $join->on('daily_numbers.user_id', '=', 'users.id');
    //         })
    //         ->select(DB::raw('sum(daily_numbers.hours) as hours, users.office, users.first_name, users.last_name, users.id'))
    //         ->groupBy('users.id')
    //         ->whereNotNull('daily_numbers.hours')
    //         ->orderByDesc('hours')
    //         ->take(10)
    //         ->get();

    //     $this->top10Sets = User::query()
    //         ->leftJoin('daily_numbers', function($join) {
    //             $join->on('daily_numbers.user_id', '=', 'users.id');
    //         })
    //         ->select(DB::raw('sum(daily_numbers.sets) as sets, users.office, users.first_name, users.last_name, users.id'))
    //         ->groupBy('users.id')
    //         ->whereNotNull('daily_numbers.sets')
    //         ->orderByDesc('sets')
    //         ->take(10)
    //         ->get();

    //     $this->top10SetCloses = User::query()
    //         ->leftJoin('daily_numbers', function($join) {
    //             $join->on('daily_numbers.user_id', '=', 'users.id');
    //         })
    //         ->select(DB::raw('sum(daily_numbers.set_closes) as set_closes, users.office, users.first_name, users.last_name, users.id'))
    //         ->groupBy('users.id')
    //         ->whereNotNull('daily_numbers.set_closes')
    //         ->orderByDesc('set_closes')
    //         ->take(10)
    //         ->get();
    // }

    public function setUser($userId)
    {
        $this->userId = $userId;

        $this->user = User::find($userId);

        $this->first_name = $this->user->first_name;
        $this->last_name  = $this->user->last_name;
        $this->office     = $this->user->office;

        $query = $this->user->dailyNumbers;

        $this->totalDoors  = $query->sum('doors');
        $this->totalHours  = $query->sum('hours');
        $this->totalSets   = $query->sum('sets');
        $this->totalSits   = $query->sum('sits');
        $this->totalCloses = $query->sum('set_closes');

        $this->dpsRatio   = $this->totalDoors / $this->totalSets;
        $this->hpsRatio   = $this->totalHours / $this->totalSets;
        $this->sitRatio   = $this->totalSits / $this->totalSets;
        $this->closeRatio = $this->totalCloses / $this->totalSits;
    }

    public function render()
    {
        $this->filterTypes = [
            ['index' => 'leaderboards',   'value' => 'Leaderboards'],
            ['index' => 'records',        'value' => 'Records'],
        ];

        $this->top10Hours = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            })
            ->select(DB::raw('sum(daily_numbers.hours) as hours, users.office, users.first_name, users.last_name, users.id'))
            ->groupBy('users.id')
            ->whereNotNull('daily_numbers.hours')
            ->orderByDesc('hours')
            ->take(10)
            ->get();

        $this->top10Sets = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            })
            ->select(DB::raw('sum(daily_numbers.sets) as sets, users.office, users.first_name, users.last_name, users.id'))
            ->groupBy('users.id')
            ->whereNotNull('daily_numbers.sets')
            ->orderByDesc('sets')
            ->take(10)
            ->get();

        $this->top10SetCloses = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            })
            ->select(DB::raw('sum(daily_numbers.set_closes) as set_closes, users.office, users.first_name, users.last_name, users.id'))
            ->groupBy('users.id')
            ->whereNotNull('daily_numbers.set_closes')
            ->orderByDesc('set_closes')
            ->take(10)
            ->get();

        return view('livewire.scoreboard');
    }
}
