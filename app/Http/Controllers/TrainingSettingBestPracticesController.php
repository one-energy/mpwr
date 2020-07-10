<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingSettingBestPracticesController extends Controller
{
    public function index(Request $request)
    {
        return view('training.setting.best-practices.index');
    }
}
