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

    public function mount()
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
    }

    public function render()
    {
        return view('livewire.scoreboard');
    }
}
