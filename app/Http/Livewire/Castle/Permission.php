<?php

namespace App\Http\Livewire\Castle;

use App\Models\User;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Permission extends Component
{
    use FullTable;

    public function sortBy()
    {
        return 'first_name';
    }

    public function render()
    {
        $query = User::query();
        if(user()->role == "Department Manager"){
            $query->whereDepartmentId(user()->department_id)
                ->where('role', '!=', 'Admin')
                ->where('role', '!=', 'Owner');
        }
        if(user()->role == "Admin"){
            $query->where('role', '!=', 'Owner');
        }
        
        return view('livewire.castle.permission', [
            'users' => $query
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }
}
