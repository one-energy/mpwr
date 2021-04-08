<?php

namespace App\Http\Livewire\Castle;

use App\Models\Rates;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Rate extends Component
{
    use FullTable;

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        $ratesQuery = Rates::query();

        if (user()->role == 'Department Manager') {
            $ratesQuery->where('rates.department_id', '=', user()->department_id);
        }

        if (user()->role == 'Owner' || user()->role == 'Admin') {
            $ratesQuery;
        }

        return view('livewire.castle.rate', [
            'rates' => $ratesQuery
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
