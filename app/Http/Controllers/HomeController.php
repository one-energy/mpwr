<?php

namespace App\Http\Controllers;

use App\Models\Customer;

class HomeController extends Controller
{
    public function __invoke()
    {
        $sortOptions = [
            'Active', 
            'Inactive',
        ];

        $customers = Customer::CUSTOMERS;

        return view('home',compact('sortOptions', 'customers'));
    }
}
