<?php

namespace App\Http\Controllers;

use App\Facades\Actions\SpreadsheetDailyNumbers;
use App\Facades\Actions\UpdateOrCreateNumberTracking;
use App\Models\DailyNumber;
use Throwable;

class NumberTrackingController extends Controller
{
    public function index()
    {
        $this->authorize('viewList', DailyNumber::class);

        return view('number-tracking');
    }

    public function create()
    {
        return view('number-tracking.create');
    }

    public function store()
    {
        if (collect(request()->numbers)->isEmpty()) {
            alert()
                ->withTitle(__('Nothing was saved :('))
                ->withColor('red')
                ->send();

            return back();
        }

        UpdateOrCreateNumberTracking::execute(request()->all());

        alert()
            ->withTitle(__('Daily Numbers saved!'))
            ->send();

        return back();
    }

    public function spreadsheet()
    {
        return view('number-tracking.spreadsheet');
    }

    public function updateOrCreateDailyNumbers()
    {
        request()->validate(['dailyNumbers' => 'required|array']);

        try {
            SpreadsheetDailyNumbers::execute(request()->dailyNumbers);

            alert()
                ->withTitle(__('Number Trackers saved!'))
                ->send();
        } catch (Throwable $exception) {
            alert()
                ->withTitle(__('Something went wrong on save Number Trackers!'))
                ->withColor('red')
                ->send();
        }

        return redirect()->route('number-tracking.spreadsheet');
    }
}
