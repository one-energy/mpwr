<?php

namespace App\Http\Livewire\Castle\Users;

use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use App\Rules\Castle\DepartmentHasOffice;
use App\Traits\Livewire\Actions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Livewire\Component;

class UserInfoTab extends Component
{
    use Actions;
    use AuthorizesRequests;

    public User $user;

    public User $userOverride;

    public Collection $departmentUsers;

    public Collection $departmentManagerUsers;

    public Collection $regionManagerUsers;

    public Collection $officeManagerUsers;

    public $openedTab = 'userInfo';

    public $selectedDepartmentId;

    public $roles;

    public $departments;

    public $teams;

    public $offices;

    protected $queryString = ['openedTab'];

    public function mount(User $user)
    {
        $this->authorize('show', [User::class, $user]);

        $this->userOverride           = clone $user;
        $this->selectedDepartmentId   = $user->department_id;
        $this->departmentUsers        = collect();
        $this->departmentManagerUsers = collect();
        $this->regionManagerUsers     = collect();
        $this->officeManagerUsers     = collect();
    }

    public function render()
    {
        $department = Department::find($this->selectedDepartmentId);

        $this->departments = Department::get();
        $this->roles       = User::getRolesPerUserRole(user());
        $this->offices     = optional($department)->offices ?? collect();

        if ($this->user->department !== null) {
            $this->departmentUsers        = $this->user->department->users()->where('id', '!=', $this->user->id)->orderBy('first_name')->orderBy('last_name')->get();
            $this->departmentManagerUsers = $this->user->department->users()->whereRole('Department Manager')->orderBy('first_name')->orderBy('last_name')->get();
            $this->regionManagerUsers     = $this->user->department->users()->whereRole('Region Manager')->orderBy('first_name')->orderBy('last_name')->get();
            $this->officeManagerUsers     = $this->user->department->users()->whereRole('Office Manager')->orderBy('first_name')->orderBy('last_name')->get();
        }

        $this->getAssignedTeams();

        return view('livewire.castle.users.user-info-tab');
    }

    protected function rules()
    {
        return [
            'user.first_name'                          => 'required|string|max:255',
            'user.last_name'                           => 'required|string|max:255',
            'user.role'                                => 'nullable|in:' . implode(',', User::getRoleByNames()),
            'user.office_id'                           => ['nullable', new DepartmentHasOffice($this->user['department_id'])],
            'user.phone_number'                        => 'nullable',
            'user.pay'                                 => 'nullable',
            'user.department_id'                       => 'nullable|exists:departments,id',
            'user.email'                               => 'required|email:rfc,filter|unique:users,email,' . $this->user->id,
            'userOverride.pay'                         => 'nullable',
            'userOverride.recruiter_id'                => 'nullable|not_in:' . $this->user->id,
            'userOverride.referral_override'           => 'nullable',
            'userOverride.office_manager_id'           => 'nullable|in:' . $this->user->department?->users->pluck('id')->join(','),
            'userOverride.region_manager_id'           => 'nullable',
            'userOverride.department_manager_id'       => 'nullable',
            'userOverride.department_manager_override' => 'nullable',
            'userOverride.region_manager_override'     => 'nullable',
            'userOverride.office_manager_override'     => 'nullable',
            'userOverride.misc_override_one'           => 'nullable',
            'userOverride.misc_override_two'           => 'nullable',
            'userOverride.note_one'                    => 'nullable',
            'userOverride.note_two'                    => 'nullable',
            'userOverride.payee_one'                   => 'nullable',
            'userOverride.payee_two'                   => 'nullable',
        ];
    }

    public function update()
    {
        $this->validate();
        $this->user->phone_number = preg_replace('/\D/', '', $this->user->phone_number);
        $this->user->office_id    = $this->user->office_id == '' ? null : $this->user->office_id;

        $this->user->save();

        alert()
            ->withTitle(__('User has been updated!'))
            ->send();

        return redirect(route('castle.users.show', $this->user->id));
    }

    public function saveOverride()
    {
        $this->validate();

        $this->userOverride->recruiter_id          = $this->userOverride->recruiter_id != '' ? $this->userOverride->recruiter_id : null;
        $this->userOverride->office_manager_id     = $this->userOverride->office_manager_id != '' ? $this->userOverride->office_manager_id : null;
        $this->userOverride->region_manager_id     = $this->userOverride->region_manager_id != '' ? $this->userOverride->region_manager_id : null;
        $this->userOverride->department_manager_id = $this->userOverride->department_manager_id != '' ? $this->userOverride->department_manager_id : null;

        $this->userOverride->save();
        $this->user = clone $this->userOverride;
        alert()
            ->withTitle(__('User has been updated!'))
            ->livewire($this)
            ->send();

        $this->openedTab = 'payInfo';
    }

    public function changeTab($selectedTab)
    {
        $this->openedTab = $selectedTab;
    }

    public function userRole($userRole)
    {
        $roles     = User::ROLES;
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
        if ($this->user->role == 'Department Manager' || $this->user->role == 'Admin' || $this->user->role == 'Owner' || $this->user->role == 'Sales Rep' || $this->user->role == 'Setter') {
            $this->teams = $this->user->office ? collect([$this->user->office]) : null;
        }

        if ($this->user->role == 'Region Manager') {
            $this->teams = $this->user->managedRegions;
        }

        if ($this->user->role == 'Office Manager') {
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
