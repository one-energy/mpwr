<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    public function __invoke()
    {
        $query = Customer::query()->where(function ($query) {
            $query->orWhere('opened_by_id', user()->id)
                ->orWhere('sales_rep_id', user()->id)
                ->orWhere('setter_id', user()->id);
        });

        $sortTypes = [
            ['index' => 'all', 'value' => 'All'],
            ['index' => 'is_active', 'value' => 'Active'],
            ['index' => 'is_inactive', 'value' => 'Inactive'],
        ];

        $query
            ->when(request()->has('sort_by') && request()->sort_by === 'is_active', function (Builder $query) {
                $query->whereIsActive(true);
            })
            ->when(request()->has('sort_by') && request()->sort_by === 'is_inactive', function (Builder $query) {
                $query->whereIsActive(false);
            });
            
        return view('home', [
            'customers'       => $query->get(),
            'userLevel'       => user()->level() ?? null,
            'userEniumPoints' => user()->eniumPoints() ?? 0,
            'stockPoints'     => user()->stockPoints(),
            'sortTypes'       => $sortTypes,
        ]);
    }
}
