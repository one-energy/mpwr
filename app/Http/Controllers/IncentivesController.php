<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Incentive;

class IncentivesController extends Controller
{
    public function __invoke()
    {
        if (user()->role == 'Admin' || user()->role == 'Owner') {
            $incentives = Incentive::all();
        } else {
            $incentives = Incentive::query()->whereDepartmentId(user()->department_id)->orderBy('number_installs')->get();
        }

        $customers = Customer::query()->installed()->get();

        $myInstalls = $customers->count();

        $systemSizeSum = $customers->sum('system_size');

        $myKws = 0;

        if ($myInstalls) {
            $myKws = $systemSizeSum / $myInstalls;
        }

        return view('incentives', [
            'incentives' => $incentives,
            'myInstalls' => $myInstalls,
            'myKws'      => $myKws,
        ]);
    }
}
