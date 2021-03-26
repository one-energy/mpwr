<?php

namespace App\Http\Livewire\Reports;

use App\Models\Customer;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ReportsOverview extends Component
{
    use FullTable;

    public $startDate;

    public $endDate;

    public function render()
    {
        return view('livewire.reports.reports-overview', [
            'userCustomers' => $this->getUserCustomers(),
            'customers' => Customer::query()
                            ->search($this->search)
                            ->paginate($this->perPage),
        ]);
    }

    public function getUserCustomers()
    {
        return Customer::whereSetterId(user()->id)->get();
    }

    public function sortBy()
    {
        return 'first_name';
    }
}
