<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingSettingsBestPracticesWhatToSayController extends Controller
{
    public function index(Request $request)
    {
        return view('training.settings.best-practices.what-to-say.index');
    }
}
