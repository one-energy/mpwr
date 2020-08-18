<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingSettingsBestPracticesController extends Controller
{
    public function index(Request $request)
    {
        return view('training.settings.best-practices.index');
    }
}
