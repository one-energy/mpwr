<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('scoreboard');
    }
}
