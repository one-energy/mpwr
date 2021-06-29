<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class AreaChart extends Component
{
    public $period = 'w';

    public $totalIncome;

    public $comparativeIncome;

    public $comparativeIncomePercentage;

    public $chartTitle = 'Projected Income';

    public $panelSold = false;

    public array $data;

    public function mount()
    {
        $this->data = [];

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

        if (in_array($this->period, $this->availablePeriods(), true)) {
            [$currentDate, $pastDate] = $this->getMatchedDate($this->period);

            $currentQuery->dateOfSaleInPeriod($this->period, $currentDate);
            $pastQuery->dateOfSaleInPeriod($this->period, $pastDate);
        }

        $this->data = $this->getMappedCustomers($currentQuery);

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
        if ($this->chartTitle === 'Projected Income') {
            $this->chartTitle = 'Actual Income';
            $this->panelSold  = true;
        } else {
            $this->chartTitle = 'Projected Income';
            $this->panelSold  = false;
        }

        $this->setPeriod($this->period);
    }

    private function availablePeriods()
    {
        return ['w', 'm', 's', 'y'];
    }

    public function getMappedCustomers(Builder $currentQuery): array
    {
        return $currentQuery
            ->oldest('date_of_sale')
            ->get()
            ->map(function (Customer $customer) {
                return [
                    'commission' => $customer->sales_rep_comission,
                    'date'       => $customer->date_of_sale->format('m-d-Y')
                ];
            })
            ->values()
            ->toArray();
    }

    private function getMatchedDate(string $period): array
    {
        return match ($period) {
            'w' => [today(), today()->subWeek()],
            'm' => [today(), today()->subMonth()],
            's', 'y' => [today(), today()->subYear()]
        };
    }
}
