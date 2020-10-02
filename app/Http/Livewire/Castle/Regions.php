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
        if(user()->role == "Department Manager"){
            $regions = Region::query()->select('regions.*')
                ->join('departments', 'regions.department_id', '=', 'departments.id')
                ->where('departments.department_manager_id', '=', user()->id);
        }else{
            $regions = Region::query();
        }
        return view('livewire.castle.regions', [
            'regions' =>  $regions
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
