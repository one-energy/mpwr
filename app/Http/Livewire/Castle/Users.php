<?php

namespace App\Http\Livewire\Castle;

use App\Models\User;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Users extends Component
{
    use FullTable;

    public $roles;

    public function sortBy()
    {
        return 'first_name';
    }

    public function render()
    {
        $this->roles = User::ROLES;
        $query = User::with('office')->select('users.*');
        if (user()->role == "Office Manager") {
            $userOfficeId = user()->id;
            $query->join('offices', function($join) use ($userOfficeId) {
                $join->on('users.office_id', '=', 'offices.id')
                        ->where('offices.office_manager_id', '=', $userOfficeId);
            });
        }
        if (user()->role == "Region Manager") {
            $userRegionId = user()->id;
            $query->join('offices', 'users.office_id', '=', 'offices.id');
            $query->join('regions', function($join) use ($userRegionId) {
                $join->on('offices.region_id', '=', 'regions.id')
                        ->where('regions.region_manager_id', "=", $userRegionId);
            });
        }
        if (user()->role == "Department Manager") {
            $query->whereDepartmentId(user()->department_id)
                    ->where("role", "!=", "Admin")
                    ->where("role", "!=", "Owner");
        }
        if (user()->role == "Admin") {
            $query->where("role", "!=", "Owner");
        }

        return view('livewire.castle.users', [
            'users' => $query
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ]);
    }

    public function userRole($userRole)
    {
        $roleTitle = '';
        foreach ($this->roles as $role) {
            if ($role['name'] == $userRole) {
                $roleTitle = $role['title'];
            }
        }
        return $roleTitle;
    }
}
