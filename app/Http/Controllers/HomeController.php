<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = Customer::query();

        $sortTypes = [
            ['index' => 'is_active', 'value' => 'Active'],
            ['index' => 'is_inactive', 'value' => 'Inactive'],
            ['index' => 'all', 'value' => 'All'],
        ];

        $query
            ->when(request()->has('sort_by') && request()->sort_by == 'is_active', function (Builder $query) {
                $query->where('is_active', true);
            })
            ->when(request()->has('sort_by') && request()->sort_by == 'is_inactive', function (Builder $query) {
                $query->where('is_active', false);
            })
            ->orderByDesc('is_active');

        return view('home', [
            'customers' => $query->get(),
            'sortTypes' => $sortTypes,
        ]);
    }
}
