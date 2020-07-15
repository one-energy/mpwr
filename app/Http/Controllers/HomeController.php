<?php

namespace App\Http\Controllers;

use App\Models\Customer;

class HomeController extends Controller
{
    public function __invoke()
    {
        $customers = Customer::query()->get();

        return view('home',compact('customers'));
    }
}
