<?php

namespace App\Http\Livewire\Castle;

use App\Models\Department;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Departments extends Component
{
    use FullTable;

    public function sortBy()
    {
        return 'name';
    }

    public function render()
    {
        return view('livewire.castle.departments', [
            'departments' => Department::join('users', 'users.id', '=', 'departments.department_manager_id')
                ->select('departments.*', 'users.first_name', 'users.last_name')
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
