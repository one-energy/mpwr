<?php

namespace App\Http\Controllers;

use App\Models\DailyNumber;
use Illuminate\Http\Request;

class NumberTrackingController extends Controller
{
    public function index(Request $request)
    {
        $showOptions = [
            'Daily Total', 
            'Weekly Total',
            'Monthly Total',
            'Statistics',
        ];
        $trackingInformation = [
            ['team_member' => 'Donna Walker',    'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['team_member' => 'Chris Wiliams',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['team_member' => 'Ana Hendersen',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['team_member' => 'Donald Barnes',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['team_member' => 'Joe Richardson',  'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['team_member' => 'Tammy Collins',   'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['team_member' => 'Joseph Bennett',  'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['team_member' => 'Michelle Powell', 'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['team_member' => 'Jerry Kelly',     'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
            ['team_member' => 'Donna Walker',    'doors' => 100, 'hours' => 9, 'sets' => 8, 'sits' => 2, 'set_closes' => 1, 'closes' => 1],
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

        if (!empty($data['numbers'])) {
            $date = ($data['date']) ?? date('Y-m-d', time()); 

            foreach ($data['numbers'] as $userId => $numbers) {
                if (!empty(array_filter($numbers))) {
                    DailyNumber::updateOrCreate(
                        [
                            'user_id' => $userId,
                            'date' => $date
                        ],
                        $numbers
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
