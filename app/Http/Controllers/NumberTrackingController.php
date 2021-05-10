<?php

namespace App\Http\Controllers;

use App\Facades\Actions\UpdateOrCreateNumberTracking;
use App\Models\DailyNumber;
use Illuminate\Http\Response;

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
        abort_if(user()->hasAnyRole(['Setter', 'Sales Rep']), Response::HTTP_NOT_FOUND);

        return view('number-tracking.spreadsheet');
    }
}
