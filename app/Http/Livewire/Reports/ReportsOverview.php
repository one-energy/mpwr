<?php

namespace App\Http\Livewire\Reports;

use App\Models\Customer;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class ReportsOverview extends Component
{
    use FullTable;

    public function render()
    {
        // $this->customers = ;
        return view('livewire.reports.reports-overview', [
            'customers' => Customer::query()
                            ->search($this->search)
                            ->orderBy($this->sortBy)
                            ->paginate($this->perPage),
        ]);
    }

    public function sortBy()
    {
        return 'first_name';
    }
}
