<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AreaChart extends Component
{
    public $period = 'w';

    public $income;

    public $incomeDate;

    public $totalIncome;

    public $comparativeIncome;

    public $comparativeIncomePercentage;

    public $customers;

    public $chartTitle = 'Projected Income';

    public $panelSold = false;

    public function mount()
    {
        $this->setPeriod($this->period);
    }

    public function render()
    {
        return view('livewire.area-chart');
    }

    public function setPeriod($period)
    {
        $this->period = $period;
        
        $userId = Auth::user()->id;

        if ($this->panelSold == true) {
            $currentQuery = Customer::query()->where([
                ['sales_rep_id', $userId],
                ['is_active', true],
                ['panel_sold', true],
            ]);

            $pastQuery = Customer::query()->where([
                ['sales_rep_id', $userId],
                ['is_active', true],
                ['panel_sold', true],
            ]);
        } else {
            $currentQuery = Customer::query()->where([
                ['sales_rep_id', $userId],
                ['is_active', true],
            ]);

            $pastQuery = Customer::query()->where([
                ['sales_rep_id', $userId],
                ['is_active', true],
            ]);
        }

        if ($period === 'w') {
            $pastQuery    = $pastQuery->whereBetween('date_of_sale', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
            $currentQuery = $currentQuery->whereBetween('date_of_sale', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'm') {
            $pastQuery    = $pastQuery->whereMonth('date_of_sale', '=', Carbon::now()->subMonth()->month);
            $currentQuery = $currentQuery->whereMonth('date_of_sale', '=', Carbon::now()->month);
        } elseif ($period === 's') {
            $currentYear = Carbon::now()->year;
            $pastYear    = Carbon::now()->year - 1;

            $pastQuery    = $pastQuery->whereBetween('date_of_sale', [$pastYear . '-06-01', $pastYear . '-08-31']);
            $currentQuery = $currentQuery->whereBetween('date_of_sale', [$currentYear . '-06-01', $currentYear . '-08-31']);
        } elseif ($period === 'y') {
            $currentYear = Carbon::now()->year;
            $pastYear    = Carbon::now()->year - 1;

            $pastQuery    = $pastQuery->whereYear('date_of_sale', '=', $pastYear);
            $currentQuery = $currentQuery->whereYear('date_of_sale', '=', $currentYear);
        }

        $this->customers  = $currentQuery->get();
        $this->income     = $currentQuery->pluck('sales_rep_comission')->toArray();
        $this->incomeDate = $currentQuery->pluck('date_of_sale')->toArray();

        $this->sumIncome($pastQuery, $currentQuery);
    }

    public function sumIncome($pastCustomers, $currentCustomers)
    {
        if ($this->panelSold) {
            $pastTotalIncome = $pastCustomers->where([
                ['is_active', true],
                ['panel_sold', true],
            ])->sum('sales_rep_comission');
    
            $currentTotalIncome = $currentCustomers->where([
                ['is_active', true],
                ['panel_sold', true],
            ])->sum('sales_rep_comission');
        } else {
            $pastTotalIncome = $pastCustomers->where([
                ['is_active', true],
            ])->sum('sales_rep_comission');
    
            $currentTotalIncome = $currentCustomers->where([
                ['is_active', true],
            ])->sum('sales_rep_comission');
        }
        
        $this->totalIncome = $currentTotalIncome;

        $this->compareIncome($pastTotalIncome, $currentTotalIncome);
    }

    public function compareIncome($pastTotalIncome, $currentTotalIncome)
    {
        $this->comparativeIncome = $currentTotalIncome - $pastTotalIncome;

        if ($pastTotalIncome) {
            $this->comparativeIncomePercentage = $currentTotalIncome / $pastTotalIncome;
        }
    }

    public function toggle()
    {
        if ($this->chartTitle == 'Projected Income') {
            $this->chartTitle = 'Actual Income';
            $this->panelSold  = true;
        } else {
            $this->chartTitle = 'Projected Income';
            $this->panelSold  = false;
        }

        $this->setPeriod($this->period);
    }
}
