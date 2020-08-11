<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingSettingsController extends Controller
{
    public function index(Request $request)
    {
        return view('training.settings.index');
    }
}
