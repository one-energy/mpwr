<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ScoreboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $filterTypes = [
            ['index' => 'leaderboards',   'value' => 'Leaderboards'],
            ['index' => 'records',        'value' => 'Records'],
        ];

        $query = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            });

        $top10Hours = $query
            ->sum('daily_numbers.hours')
            ->whereNotNull('daily_numbers.hours')
            ->orderByDesc('daily_numbers.hours')
            ->take(10)
            ->get();

        $top10Sets = $query
            ->whereNotNull('daily_numbers.sets')
            ->orderByDesc('daily_numbers.sets')
            ->take(10)
            ->get();

        $top10SetCloses = $query
            ->whereNotNull('daily_numbers.set_closes')
            ->orderByDesc('daily_numbers.set_closes')
            ->take(10)
            ->get();

        return view('scoreboard',compact('filterTypes', 'top10Hours', 'top10Sets', 'top10SetCloses'));
    }
}
