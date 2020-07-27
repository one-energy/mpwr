<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('viewList', Customer::class);

        $query = Customer::query();

        $sortTypes = [
            ['index' => 'is_active',   'value' => 'Active'],
            ['index' => 'is_inactive', 'value' => 'Inactive'],
            ['index' => 'all',         'value' => 'All'],
        ];

        if (!empty(request('sort_by'))) {
            if ($request->input('sort_by') == "is_active") {
                $query
                ->where('is_active', 1);
            } elseif ($request->input('sort_by') == "is_inactive") {
                $query
                ->where('is_active', '')->orWhere('is_active', null)->orWhere('is_active', 0);
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
