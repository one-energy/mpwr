<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Livewire\Component;

class AreaChart extends Component
{
    public $period = 'w';

    public $totalIncome;

    public $comparativeIncome;

    public $comparativeIncomePercentage;

    public $chartTitle = 'Projected Income';

    public $panelSold = false;

    public Collection $data;

    public function mount()
    {
        $this->data = collect();

        $this->setPeriod($this->period);
    }

    public function render()
    {
        return view('livewire.area-chart');
    }

    public function setPeriod($period)
    {
        $this->period = $period;

        $condition = [
            ['sales_rep_id', user()->id],
            ['is_active', true],
        ];

        if ($this->panelSold) {
            $condition[] = ['panel_sold', true];
        }

        $currentQuery = Customer::query()->where($condition);
        $pastQuery    = clone $currentQuery;

        if (in_array($this->period, ['w', 'm', 's', 'y'])) {
            [$currentDate, $pastDate] = match ($this->period) {
                'w' => [today(), today()->subWeek()],
                'm' => [today(), today()->subMonth()],
                's', 'y' => [today(), today()->subYear()]
            };

            $currentQuery->dateOfSaleInPeriod($this->period, $currentDate);
            $pastQuery->dateOfSaleInPeriod($this->period, $pastDate);
        }

        $this->data = $currentQuery
            ->oldest('date_of_sale')
            ->get()
            ->map(function (Customer $customer) {
                return [
                    'commission' => $customer->sales_rep_comission,
                    'date'       => $customer->date_of_sale->format('m-d-Y')
                ];
            })
            ->values();

        $this->sumIncome($pastQuery, $currentQuery);
    }

    public function sumIncome($pastCustomers, $currentCustomers)
    {
        $condition = [
            ['is_active', true]
        ];

        if ($this->panelSold) {
            $condition[] = ['panel_sold', true];
        }

        $pastTotalIncome    = $pastCustomers->where($condition)->sum('sales_rep_comission');
        $currentTotalIncome = $currentCustomers->where($condition)->sum('sales_rep_comission');

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
