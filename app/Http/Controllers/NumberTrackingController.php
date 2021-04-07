<?php

namespace App\Http\Controllers;

use App\Http\Requests\NumberTracking\StoreNumberTrackingRequest;
use App\Models\DailyNumber;
use App\Services\NumberTrackingService;
use Illuminate\Http\Request;

class NumberTrackingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewList', DailyNumber::class);

        return view('number-tracking');
    }

    public function create()
    {
        return view('number-tracking.create');
    }

    public function store(StoreNumberTrackingRequest $request, NumberTrackingService $service)
    {
        if (collect($request->numbers)->isEmpty()) {
            alert()
                ->withTitle(__('Nothing was saved :('))
                ->withColor('red')
                ->send();

            return back();
        }

        $service->updateOrCreateNumberTracking($request->validated());

        alert()
            ->withTitle(__('Daily Numbers saved!'))
            ->send();

        return back();
    }
}
