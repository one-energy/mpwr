<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ScoreboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('scoreboard');
    }
}
