<?php

namespace App\Http\Controllers;

use App\Models\Incentive;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class IncentivesController extends Controller
{
    public function __invoke()
    {
        $incentives    = Incentive::query()->orderBy('number_installs')->get();
        $userId        = Auth::user()->id;
        $myInstalls    = Customer::query()->where(['opened_by_id' => $userId, 'panel_sold' => true, 'is_active' => true])->count();
        $systemSizeSum = Customer::query()->where('opened_by_id', $userId)->sum('system_size');
        $myKws         = 0;
        
        if ($myInstalls)
        {
            $myKws = $systemSizeSum / $myInstalls;
        }

        return view('incentives', [
            'incentives' => $incentives,
            'myInstalls' => $myInstalls,
            'myKws'      => $myKws
        ]);
    }
}
