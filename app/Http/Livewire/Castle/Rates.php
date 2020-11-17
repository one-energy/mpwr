<?php

namespace App\Http\Livewire\Castle;

use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Rates extends Component
{
    use FullTable;

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        $officesQuery = Rates::query()->select('offices.*');

        if (user()->role == "Department Manager") {
            $officesQuery->join('rates', 'offices.region_id', '=', 'rates.id')
                ->where('rates.department_id', '=', user()->department_id);
        }

        if (user()->role == "Owner" || user()->role == "Admin") {
            $officesQuery;
        }

        return view('livewire.castle.rates', [
            'offices' => $officesQuery
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
