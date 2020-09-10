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
        return view('livewire.castle.regions', [
            'regions' => Region::join('users', 'users.id', '=', 'regions.region_manager_id')
                ->select('regions.*', 'users.first_name', 'users.last_name')
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
