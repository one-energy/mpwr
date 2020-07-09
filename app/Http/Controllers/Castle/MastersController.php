<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;

class MastersController extends Controller
{
    public function index()
    {
        return view('castle.masters.index');
    }
}
