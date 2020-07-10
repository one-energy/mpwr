<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingSettingController extends Controller
{
    public function index(Request $request)
    {
        return view('training.setting.index');
    }
}
