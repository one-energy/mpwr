<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Customer::query();

        $sortTypes = [
            ['index' => 'is_active',   'value' => 'Active'],
            ['index' => 'is_inactive', 'value' => 'Inactive'],
        ];

        if (!empty(request('sort_by')))
        {
            if($request->input('sort_by') == "is_active")
            {
                $query
                ->orderBy('is_active', 'DESC');
            }elseif($request->input('sort_by') == "is_inactive")
            {
                $query
                ->orderBy('is_active', 'ASC');
            }
        }

        return view('home',
            [
                'customers' => $query->get(),
                'sortTypes' => $sortTypes,
            ]
        );
    }
}
