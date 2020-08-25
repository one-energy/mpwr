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

        if($this->panelSold == true){
            $currentQuery = Customer::query()->where([
                ['opened_by_id', $userId],
                ['is_active', true],
                ['panel_sold', true],
            ]);

            $pastQuery = Customer::query()->where([
                ['opened_by_id', $userId],
                ['is_active', true],
                ['panel_sold', true],
            ]);
        }else{
            $currentQuery = Customer::query()->where([
                ['opened_by_id', $userId],
                ['is_active', true],
            ]);

            $pastQuery = Customer::query()->where([
                ['opened_by_id', $userId],
                ['is_active', true],
            ]);
        }

        if ($period === "w") {
            $pastQuery    = $pastQuery->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
            $currentQuery = $currentQuery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === "m") {
            $pastQuery    = $pastQuery->whereMonth('created_at', '=', Carbon::now()->subMonth()->month);
            $currentQuery = $currentQuery->whereMonth('created_at', '=', Carbon::now()->month);
        } elseif ($period === "s") {
            $currentYear = Carbon::now()->year;
            $pastYear    = Carbon::now()->year - 1;

            $pastQuery    = $pastQuery->whereBetween('created_at', [$pastYear . '-06-01', $pastYear . '-08-31']);
            $currentQuery = $currentQuery->whereBetween('created_at', [$currentYear . '-06-01', $currentYear . '-08-31']);
        } elseif ($period === "y") {
            $currentYear = Carbon::now()->year;
            $pastYear    = Carbon::now()->year - 1;

            $pastQuery    = $pastQuery->whereYear('created_at', '=', $pastYear);
            $currentQuery = $currentQuery->whereYear('created_at', '=', $currentYear);
        }

        $this->customers  = $currentQuery->get();
        $this->income     = $currentQuery->pluck('commission')->toArray();
        $this->incomeDate = $currentQuery->pluck('created_at')->toArray();

        $this->sumIncome($pastQuery, $currentQuery);
    }

    public function sumIncome($pastCustomers, $currentCustomers)
    {
        if($this->panelSold){
            $pastTotalIncome = $pastCustomers->where([
                ['is_active', true],
                ['panel_sold', true],
            ])->sum('commission');
    
            $currentTotalIncome = $currentCustomers->where([
                ['is_active', true],
                ['panel_sold', true],
            ])->sum('commission');
        }else{
            $pastTotalIncome = $pastCustomers->where([
                ['is_active', true],
            ])->sum('commission');
    
            $currentTotalIncome = $currentCustomers->where([
                ['is_active', true],
            ])->sum('commission');
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
        }else{
            $this->chartTitle = 'Projected Income';
            $this->panelSold  = false;
        }

        $this->setPeriod($this->period);
    }
}
