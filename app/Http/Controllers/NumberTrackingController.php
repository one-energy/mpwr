<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\DailyNumber;
use Illuminate\Http\Request;

class NumberTrackingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewList', DailyNumber::class);

        $showOptions = [
            'Daily Total', 
            'Weekly Total',
            'Monthly Total',
            'Statistics',
        ];
        $trackingInformation = [
            ['region_member' => 'Donna Walker',    'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Chris Wiliams',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Ana Hendersen',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Donald Barnes',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Joe Richardson',  'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Tammy Collins',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Joseph Bennett',  'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Michelle Powell', 'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Jerry Kelly',     'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['region_member' => 'Donna Walker',    'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
        ];

        return view('number-tracking',compact('showOptions', 'trackingInformation'));
    }

    public function create()
    {
        return view('number-tracking.create');
    }

    public function store()
    {
        $data = request()->all();

        $this->authorize('update', [DailyNumber::class, $data['officeSelected']]);

        if (!empty($data['numbers'])) {
            $date = ($data['date']) ? date('Y-m-d', strtotime($data['date'])) : date('Y-m-d', time()); 

            foreach ($data['numbers'] as $userId => $numbers) {
                $filteredNumbers = array_filter($numbers, function ($element) {
                    return ($element >= 0 && !is_null($element));
                });

                if (!empty($filteredNumbers)) {
                    DailyNumber::updateOrCreate(
                        [
                            'user_id' => $userId,
                            'date'    => $date,
                        ],
                        $filteredNumbers
                    );
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