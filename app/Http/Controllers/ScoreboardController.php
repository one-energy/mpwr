<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $filterTypes = [
            ['index' => 'leaderboards',   'value' => 'Leaderboards'],
            ['index' => 'records',        'value' => 'Records'],
        ];

        $data = [
            ['id' => 1,  'representative' => 'Maren Decker',     'set_closes' => 6, 'office' => 'Fresno'],
            ['id' => 2,  'representative' => 'Braden Harris',    'set_closes' => 5, 'office' => 'Fresno'],
            ['id' => 3,  'representative' => 'Starling Infante', 'set_closes' => 4, 'office' => 'Fairfield'],
            ['id' => 4,  'representative' => 'Gavin Bellman',    'set_closes' => 3, 'office' => 'High Desert'],
            ['id' => 5,  'representative' => 'Gordon Gygl',      'set_closes' => 2, 'office' => 'High Desert'],
            ['id' => 6,  'representative' => 'Jayden Agnello',   'set_closes' => 2, 'office' => 'High Desert'],
            ['id' => 7,  'representative' => 'Brandyn Bailey',   'set_closes' => 2, 'office' => 'Fairfield'],
            ['id' => 8,  'representative' => 'Clay Nesser',      'set_closes' => 2, 'office' => 'Fresno'],
            ['id' => 9,  'representative' => 'Justin Jones',     'set_closes' => 2, 'office' => 'Stockton'],
            ['id' => 10, 'representative' => 'Brock Cloward',    'set_closes' => 2, 'office' => 'Stockton'],
        ];

        return view('scoreboard',compact('data', 'filterTypes'));
    }
}
