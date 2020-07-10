<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncentivesController extends Controller
{
    public function __invoke()
    {
        $data = [
            ['number_installs' => 45,  'incentive' => 'Thailand',    'installs_achieved' => 110, 'installs_needed' => 0,  'kw_achieved' => 101, 'kw_needed' => 45],
            ['number_installs' => 50,  'incentive' => 'Plus 1',      'installs_achieved' => 100, 'installs_needed' => 0,  'kw_achieved' => 90,  'kw_needed' => 45],
            ['number_installs' => 65,  'incentive' => 'First Class', 'installs_achieved' => 90,  'installs_needed' => 15, 'kw_achieved' => 60,  'kw_needed' => 70],
            ['number_installs' => 80,  'incentive' => 'Model 3',     'installs_achieved' => 80,  'installs_needed' => 20, 'kw_achieved' => 40,  'kw_needed' => 90],
            ['number_installs' => 100, 'incentive' => 'Model S',     'installs_achieved' => 70,  'installs_needed' => 25, 'kw_achieved' => 30,  'kw_needed' => 100],
            ['number_installs' => 150, 'incentive' => 'Model X',     'installs_achieved' => 60,  'installs_needed' => 30, 'kw_achieved' => 20,  'kw_needed' => 120],
        ];

        return view('incentives',compact('data'));
    }
}
