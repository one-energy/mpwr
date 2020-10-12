<?php

namespace App\Http\Livewire\Castle;

use App\Models\Office;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Offices extends Component
{
    use FullTable;

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        $officesQuery = Office::query()->select('offices.*');
        
        if(user()->role == "Region Manager"){
            $officesQuery->join('regions', 'offices.region_id', '=', 'regions.id')
                ->where('regions.region_manager_id', '=', user()->id);
        }

        if(user()->role == "Department Manager"){
            $officesQuery->join('regions', 'offices.region_id', '=', 'regions.id')
                ->where('regions.department_id', '=', user()->department_id);
        }

        if(user()->role == "Office Manager"){
            $officesQuery->where('office_manager_id', '=', user()->id);
        }

        if(user()->role == "Owner" || user()->role == "Admin"){
            $officesQuery;
        }

        return view('livewire.castle.offices', [
            'offices' => $officesQuery
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}