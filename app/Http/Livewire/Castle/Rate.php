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

        if (user()->hasRole('Department Manager')) {
            $ratesQuery->where('rates.department_id', '=', user()->department_id);
        }

        return view('livewire.castle.rate', [
            'rates' => $ratesQuery
                ->with('department')
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
