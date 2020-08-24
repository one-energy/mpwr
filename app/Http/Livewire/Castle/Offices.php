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
        return view('livewire.castle.offices', [
            'offices' => Office::join('regions', 'regions.id', '=', 'offices.region_id')
                ->join('users', 'users.id', '=', 'offices.office_manager_id')
                ->select('offices.*', 'regions.name as region', 'users.first_name', 'users.last_name')
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}