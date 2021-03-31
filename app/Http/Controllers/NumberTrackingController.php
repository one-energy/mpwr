<?php

namespace App\Http\Controllers;

use App\Models\DailyNumber;
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

    public function store()
    {
        $data = request()->all();

        // $this->authorize('update', [DailyNumber::class, $data['officeSelected']]);

        if (!empty($data['numbers'])) {
            $date = ($data['date']) ? date('Y-m-d', strtotime($data['date'])) : date('Y-m-d', time());

            foreach ($data['numbers'] as $userId => $numbers) {
                $filteredNumbers = array_filter($numbers, function ($element) {
                    return ($element >= 0);
                });

                $isEmpty = empty(array_filter($filteredNumbers, function($item) {
                    return $item !== null;
                }));

                if (!$isEmpty) {
                    DailyNumber::updateOrCreate(
                        [
                            'user_id' => $userId,
                            'date'    => $date,
                        ],
                        $filteredNumbers
                    );
                } else {
                    $dailyNumber = DailyNumber::whereDate('date', $date)->whereUserId($userId)->first();
                    if (!empty($dailyNumber)) {
                        DailyNumber::destroy($dailyNumber->id);
                    }
                }
            }
            alert()
                ->withTitle(__('Daily Numbers saved!'))
                ->send();
        } else {
            alert()
                ->withTitle(__('Nothing was saved :('))
                ->withColor('red')
                ->send();
        }

        return back();
    }
}
