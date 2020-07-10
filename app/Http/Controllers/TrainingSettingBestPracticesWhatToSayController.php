<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrainingSettingBestPracticesWhatToSayController extends Controller
{
    public function index(Request $request)
    {
        return view('training.setting.best-practices.what-to-say.index');
    }
}
