<?php

namespace App\Http\Livewire\Castle\Users;

use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use Illuminate\Validation\Rule;
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

    public function mount($user)
    {
        $this->selectedDepartmentId = $user->department_id;
    }

    public function render()
    {
        $department        = Department::find($this->selectedDepartmentId);
        $this->departments = Department::get();
        $this->roles       = User::getRolesPerUserRole(user());
        $this->offices     = $department ? $department->offices()->get() : [];
        $this->getAssignedTeams();

        return view('livewire.castle.users.user-info-tab');
    }

    protected function rules()
    {
        return [
            'user.first_name'    => ['required', 'string', 'max:255'],
            'user.last_name'     => ['required', 'string', 'max:255'],
            'user.role'          => ['nullable', 'string', 'max:255'],
            'user.office_id'     => 'nullable',
            'user.pay'           => 'nullable',
            'user.department_id' => 'nullable',
            'user.email'         => 'required|unique:users,email,' . $this->user->id,
        ];
    }

    public function update()
    {
        $this->validate();
        // $data = request()->validate([
        //     'first_name'    => ['required', 'string', 'min:3', 'max:255'],
        //     'last_name'     => ['required', 'string', 'min:3', 'max:255'],
        //     'role'          => ['nullable', 'string', 'max:255'],
        //     'office_id'     => ['nullable', 'numeric'],
        //     'pay'           => ['nullable', 'numeric'],
        //     'department_id' => ['nullable', 'numeric'],
        //     'email'         => ['required', 'email', 'min:2', 'max:128', Rule::unique('users')->ignore($this->user->id)],
        // ]);

        $this->user->save();

        alert()
            ->withTitle(__('User has been updated!'))
            ->livewire($this)
            ->send();

        $this->openedTab = 'userInfo';
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

    public function changeDepartment(int $departmentId): void
    {
        $this->selectedDepartmentId = $departmentId;
    }

    public function changeRole(string $role): void
    {
        $canChange       = User::userCanChangeRole($this->user);
        $this->canChange = $canChange['status'];
        if ($canChange['status']) {
            $this->user->pay = Rates::whereRole($role)->first()->rate ?? $this->user->pay;
        } else {
            $this->showModal([
                'icon'  => 'warning',
                'title' => 'Warning!!',
                'text'  => $canChange['message'],
            ]);
        }
    }
}
