<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AreaChart extends Component
{
    public $period;

    public $income;

    public $incomeDate;

    public $totalIncome;

    public $comparativeIncome;

    public $comparativeIncomePercentage;

    public $customers;

    public function mount()
    {
        $period = 'w';

        $this->setPeriod($period);
    }

    public function render()
    {
        return view('livewire.area-chart');
    }

    public function setPeriod($period)
    {
        $this->period = $period;

        $userId = Auth::user()->id;

        $currentQuery = Customer::query()->where('opened_by_id', $userId);
        $pastQuery    = Customer::query()->where('opened_by_id', $userId);

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
        $pastTotalIncome    = $pastCustomers->where('is_active', 1)->sum('commission');
        $currentTotalIncome = $currentCustomers->where('is_active', 1)->sum('commission');

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
}
