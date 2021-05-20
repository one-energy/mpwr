<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $userId = Auth::user()->id;

        $query = Customer::query()->where(function ($query) use ($userId) {
            $query->orWhere('opened_by_id', $userId)
            ->orWhere('sales_rep_id',$userId)
            ->orWhere('setter_id', $userId);
        });

        $sortTypes = [
            ['index' => 'all', 'value' => 'All'],
            ['index' => 'is_active', 'value' => 'Active'],
            ['index' => 'is_inactive', 'value' => 'Inactive'],
        ];

        $query
            ->when(request()->has('sort_by') && request()->sort_by == 'is_active', function (Builder $query) {
                $query->whereIsActive(true);
            })
            ->when(request()->has('sort_by') && request()->sort_by == 'is_inactive', function (Builder $query) {
                $query->whereIsActive(false);
            });

        return view('home', [
            'customers'       => $query->get(),
            'userLevel'       => user()->level() ?? null,
            'userEniumPoints' => user()->eniumPoints() ?? 0,
            'sortTypes' => $sortTypes,
        ]);
    }
}
