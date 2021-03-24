<?php

namespace App\Http\Livewire\Castle\Users;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;

class UserInfoTab extends Component
{
    public User $user;

    public $openedTab = 'userInfo';

    public $selectedDepartmentId;

    public $roles;

    public $departments;

    public $teams;

    public $offices;

    protected $queryString = ['openedTab'];

    protected $rules = [
        'user.first_name'    => ['required', 'string', 'max:255'],
        'user.last_name'     => ['required', 'string', 'max:255'],
        'user.role'          => ['nullable', 'string', 'max:255'],
        'user.office_id'     => 'nullable',
        'user.pay'           => 'nullable',
        'user.department_id' => 'nullable',
        'user.email'         => 'required',
    ];

    public function mount($user)
    {
        $this->selectedDepartmentId = $user->department_id;
    }

    public function render()
    {
        $department    = Department::find($this->selectedDepartmentId);
        $this->departments = Department::get();
        $this->roles   = User::getRolesPerUserRole(user());
        $this->offices = $department ? $department->offices()->get() : [];
        $this->getAssignedTeams();
        return view('livewire.castle.users.user-info-tab');
    }

    public function changeTab($selectedTab)
    {
        $this->openedTab = $selectedTab;
    }

    public function userRole($userRole)
    {
        $roles =  User::ROLES;
        $roleTitle = '';
        foreach ($roles as $role) {
            if ($role['name'] == $userRole) {
                $roleTitle = $role['title'];
            }
        }
        return $roleTitle;
    }

    public function getAssignedTeams()
    {
        if ($this->user->role == "Department Manager" || $this->user->role == "Admin" || $this->user->role == "Owner" || $this->user->role == "Sales Rep" || $this->user->role == "Setter" ) {
            $this->teams = collect([$this->user->office]);
        }

        if ($this->user->role == "Region Manager") {
            $this->teams = $this->user->managedRegions;
        }

        if ($this->user->role == "Office Manager") {
            $this->teams = $this->user->managedOffices;
        }
    }
}
