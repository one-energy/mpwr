<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ScoreboardController extends Controller
{
    public function __invoke(Request $request)
    {
        // $filterTypes = [
        //     ['index' => 'leaderboards',   'value' => 'Leaderboards'],
        //     ['index' => 'records',        'value' => 'Records'],
        // ];

        // $top10Hours = User::query()
        //     ->leftJoin('daily_numbers', function($join) {
        //         $join->on('daily_numbers.user_id', '=', 'users.id');
        //     })
        //     ->select(DB::raw('sum(daily_numbers.hours) as hours, users.office, users.first_name, users.last_name, users.id'))
        //     ->groupBy('users.id')
        //     ->whereNotNull('daily_numbers.hours')
        //     ->orderByDesc('hours')
        //     ->take(10)
        //     ->get();

        // $top10Sets = User::query()
        //     ->leftJoin('daily_numbers', function($join) {
        //         $join->on('daily_numbers.user_id', '=', 'users.id');
        //     })
        //     ->select(DB::raw('sum(daily_numbers.sets) as sets, users.office, users.first_name, users.last_name, users.id'))
        //     ->groupBy('users.id')
        //     ->whereNotNull('daily_numbers.sets')
        //     ->orderByDesc('sets')
        //     ->take(10)
        //     ->get();

        // $top10SetCloses = User::query()
        //     ->leftJoin('daily_numbers', function($join) {
        //         $join->on('daily_numbers.user_id', '=', 'users.id');
        //     })
        //     ->select(DB::raw('sum(daily_numbers.set_closes) as set_closes, users.office, users.first_name, users.last_name, users.id'))
        //     ->groupBy('users.id')
        //     ->whereNotNull('daily_numbers.set_closes')
        //     ->orderByDesc('set_closes')
        //     ->take(10)
        //     ->get();

        // return view('scoreboard',compact('filterTypes', 'top10Hours', 'top10Sets', 'top10SetCloses'));
        return view('scoreboard');
    }
}
