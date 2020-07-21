<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Customer;
use Livewire\Component;

class AreaChart extends Component
{
    public $customers;

    public $period;

    public function render()
    {
        $query = Customer::query()
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]);

        return view('livewire.area-chart', [
            'customers' => $query->get(),
        ]);
    }

    public function setPeriod ($period)
    {
        $query = Customer::query();

        if($period === "w" )
        {
            $query
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]);
            
        }elseif($period === "m")
        {
            $query
                ->whereMonth('created_at', '=', Carbon::now()->month);

        }elseif($period === "s")
        {
            $currentYear = Carbon::now()->year;

            $query
                ->whereBetween('created_at', [$currentYear.'-06-01', $currentYear.'-08-31']);

        }elseif($period === "y")
        {
            $query
                ->whereYear('created_at', '=', Carbon::now()->year);
        }

        return view('livewire.area-chart', [
            'customers' => $query->get(),
        ]);
    }
}
