<?php

namespace App\Http\Livewire\Castle;

use App\Enum\Role;
use App\Models\User;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Users extends Component
{
    use FullTable;

    public $roles;

    public string $userOffices;

    public bool $onlyPendingUsers = false;

    public function sortBy()
    {
        return 'first_name';
    }

    public function render()
    {
        $this->roles = User::ROLES;

        return view('livewire.castle.users', [
            'users' => $this->getUsers()
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

    public function canEditUser(User $editableUser)
    {
        if (user()->hasRole('Office Manager')) {
            return $editableUser->hasAnyRole(['Sales Rep', 'Setter', 'Office Manager']);
        }

        if (user()->hasRole('Region Manager')) {
            return $editableUser->notHaveRoles(['Department Manager', 'Admin', 'Owner']);
        }

        if (user()->hasRole('Department Manager')) {
            return $editableUser->notHaveRoles(['Admin', 'Owner']);
        }

        if (user()->hasRole('Admin')) {
            return $editableUser->notHaveRoles(['Owner']);
        }

        return true;
    }

    public function userInfo(User $user)
    {
        return redirect()->route('castle.users.show', $user->id);
    }

    public function canSeeOffices(User $user)
    {
        if ($user->notHaveRoles(['Office Manager', 'Region Manager', 'Department Manager'])) {
            return false;
        }

        if ($user->hasRole('Office Manager') && $user->managedOffices->isEmpty()) {
            return false;
        }

        if ($user->hasAnyRole(['Region Manager', 'Department Manager']) && $user->office === null) {
            return false;
        }

        return true;
    }

    public function openOfficesListModal(User $user)
    {
        if ($user->hasRole('Office Manager')) {
            $this->userOffices = implode('<br />', $user->managedOffices->pluck('name')->toArray());

            return;
        }

        $this->userOffices = $user->office->name;
    }

    private function getUsers()
    {
        return User::query()
            ->when(user()->hasRole(Role::OFFICE_MANAGER), function ($query) {
                $query->whereHas('office', fn($query) => $query->where('office_manager_id', user()->id));
            })
            ->when(user()->hasRole(Role::REGION_MANAGER), function ($query) {
                $query->whereHas('office.region', fn($query) => $query->where('region_manager_id', user()->id));
            })
            ->when(user()->hasRole(Role::DEPARTMENT_MANAGER), function ($query) {
                $query->where('department_id', user()->department_id)
                    ->where('role', '!=', 'Admin')
                    ->where('role', '!=', 'Owner');
            })
            ->when(user()->hasRole(Role::ADMIN), function ($query) {
                $query->where('role', '!=', Role::OWNER);
            })
            ->when($this->onlyPendingUsers, fn ($query) =>
                $query->where('department_manager_id', null)
                    ->orWhere('region_manager_id', null)
                    ->orWhere('office_manager_id', null)
            )
            ->with(['office', 'department', 'managedOffices'])
            ->search($this->search)
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }
}
