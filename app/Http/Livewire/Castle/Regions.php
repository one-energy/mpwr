<?php

namespace App\Http\Livewire\Castle;

use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Regions extends Component
{
    use FullTable;

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        if (user()->role == "Department Manager") {
            $regions = Region::query()->select('regions.*')
                ->join('departments', 'regions.department_id', '=', 'departments.id')
                ->where('departments.department_manager_id', '=', user()->id);
        }
        if (user()->role == "Region Manager") {
            $regions = Region::query()->select('regions.*')
                ->where('region_manager_id', '=', user()->id);
        }
        if (user()->role == "Admin" || user()->role == "Owner") {
            $regions = Region::query();
        }

        return view('livewire.castle.regions', [
            'regions' => $regions
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
