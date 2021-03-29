<?php

namespace App\Http\Livewire\Reports;

use App\Models\Customer;
use App\Traits\Livewire\FullTable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ReportsOverview extends Component
{
    use FullTable;

    public Collection $customersOfUser;

    public Collection $customersOfSalesRepsRecuited;

    public array $ranges = Customer::RANGE_DATES;

    public string $rangeType = 'year_to_date';

    public $startDate = '';

    public $finalDate = '';

    public function mount()
    {
        $this->startDate = Carbon::create($this->startDate)->firstOfYear()->toString();
        $this->finalDate   = Carbon::create($this->finalDate)->toString();
        // dd($this->endDate);
    }

    public function render()
    {
        $this->getUserCustomers();
        return view('livewire.reports.reports-overview', [
            'customers' => Customer::query()
                            ->search($this->search)
                            ->paginate($this->perPage),
        ]);
    }

    public function getUserCustomers()
    {
        $this->customersOfUser              = Customer::whereSetterId(user()->id)->get();
        $this->customersOfSalesRepsRecuited = user()->customersOfSalesRepsRecuited;
    }

    public function getUserTotalCommission()
    {
        return $this->getSumRecruiterCommission($this->customersOfSalesRepsRecuited) + $this->getSumSetterCommission($this->customersOfUser);
    }

    public function getSumSetterCommission (Collection $customers)
    {
        return $customers->sum(function ($customer) {
            return $this->getSetterCommission($customer);
        });
    }

    public function getAvgSetterCommission (Collection $customers)
    {
        return $customers->avg(function ($customer) {
            return $this->getSetterCommission($customer);
        });
    }

    public function getSetterCommission (Customer $customer) {
        return $customer->setter_fee * ($customer->system_size * 1000);
    }

    public function getSumRecruiterCommission (Collection $customers)
    {
        return $customers->sum(function ($customer) {
            return $this->getRecruiterCommission($customer);
        });
    }

    public function getAvgRecruiterCommission (Collection $customers)
    {
        return $customers->avg(function ($customer) {
            return $this->getRecruiterCommission($customer);
        });
    }

    public function getRecruiterCommission (Customer $customer) {
        return $customer->referral_override * ($customer->system_size * 1000);
    }

    public function sortBy()
    {
        return 'first_name';
    }
}
