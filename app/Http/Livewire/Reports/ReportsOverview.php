<?php

namespace App\Http\Livewire\Reports;

use App\Models\Customer;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ReportsOverview extends Component
{
    use FullTable;

    public Collection $customersSetter;

    public Collection $customersSalesRep;

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
        $this->customersSetter   = Customer::whereSetterId(user()->id)->get();
        $this->customersSalesRep = Customer::whereSalesRepId(user()->id)->get();
        $this->customersSalesRep = Customer::whereSalesRepId(user()->id)->get();
    }

    public function sortBy()
    {
        return 'first_name';
    }
}
