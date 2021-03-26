<?php

namespace App\Http\Livewire\Reports;

use App\Models\Customer;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ReportsOverview extends Component
{
    use FullTable;

    public Collection $customersOfUser;

    public Collection $customersOfSalesRepsRecuited;

    public $startDate;

    public $endDate;

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

    public function getAvgSetterCommission (Collection $customers)
    {
        return $customers->avg(function ($customer) {
            return $this->getSetterCommission($customer);
        });
    }

    public function getSetterCommission (Customer $customer) {
        return $customer->setter_fee * ($customer->system_size * 1000);
    }

    public function getAvgRecruiterCommission (Collection $customers)
    {
        dd($customers);
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
