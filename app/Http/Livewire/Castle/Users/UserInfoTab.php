<?php

namespace App\Http\Livewire\Castle\Users;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Office;
use App\Models\Rates;
use App\Models\Region;
use App\Models\User;
use App\Rules\Castle\DepartmentHasOffice;
use App\Traits\Livewire\Actions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UserInfoTab extends Component
{
    use Actions;

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

    public bool $showWarningRoleModal = false;

    public string $warningRoleMessage = '';

    public string $selectedRole = '';

    public Collection | array $selectedManagers;

    public $reportsTo = null;

    public function mount(User $user)
    {
        $this->userOverride           = clone $user;
        $this->selectedDepartmentId   = $user->department_id;
        $this->departmentUsers        = collect();
        $this->departmentManagerUsers = collect();
        $this->regionManagerUsers     = collect();
        $this->officeManagerUsers     = collect();
        $this->selectedRole           = $user->role;
        $this->reportsTo              = $this->getOfficeId($user);
        $this->selectedManagers       = $this->getSelectedManagers($user);
    }

    private function getSelectedManagers(User $user)
    {
        if ($user->hasRole(Role::DEPARTMENT_MANAGER)) {
            return $user->managedDepartments->pluck('id');
        }

        if ($user->hasRole(Role::REGION_MANAGER)) {
            return $user->managedRegions->pluck('id');
        }

        if ($user->hasRole(Role::OFFICE_MANAGER)) {
            return $user->managedOffices->pluck('id');
        }

        return collect();
    }

    public function render()
    {
        $department = Department::find($this->selectedDepartmentId);

        $this->departments = Department::get();
        $this->roles       = User::getRolesPerUserRole();
        $this->offices     = optional($department)->offices ?? collect();

        if ($this->user->department !== null) {
            $this->departmentUsers        = $this->user->department->users()->where('id', '!=', $this->user->id)->orderBy('first_name')->orderBy('last_name')->get();
            $this->departmentManagerUsers = $this->user->department->users()->whereRole(Role::DEPARTMENT_MANAGER)->orderBy('first_name')->orderBy('last_name')->get();
            $this->regionManagerUsers     = $this->user->department->users()->whereRole(Role::REGION_MANAGER)->orderBy('first_name')->orderBy('last_name')->get();
            $this->officeManagerUsers     = $this->user->department->users()->whereRole(Role::OFFICE_MANAGER)->orderBy('first_name')->orderBy('last_name')->get();
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
            'user.office_id'                           => [
                'nullable',
                new DepartmentHasOffice($this->user['department_id']),
            ],
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
        $this->user->office_id    = $this->user->office_id === '' ? null : $this->user->office_id;

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
        $this->teams = match ($this->user->role) {
            Role::ADMIN, Role::OWNER, Role::SALES_REP, Role::SETTER => $this->user->office ? collect() : null,
            Role::DEPARTMENT_MANAGER => $this->user->managedDepartments,
            Role::REGION_MANAGER     => $this->user->managedRegions,
            Role::OFFICE_MANAGER     => $this->user->managedOffices,
            default                  => null
        };
    }

    public function changeDepartment(int $departmentId): void
    {
        $this->selectedDepartmentId = $departmentId;
    }

    public function changeRole(): void
    {
        $canChange = User::userCanChangeRole($this->user);

        if ($canChange['status']) {
            $this->cleanSelectedManagers();
            $this->changeUserPay();

            return;
        }

        $this->showWarningRoleModal = true;
        $this->warningRoleMessage   = $canChange['message'];
    }

    public function returnToDefaultRole()
    {
        $this->selectedRole = $this->user->role;

        $this->showWarningRoleModal = false;
    }

    public function changeUserPay()
    {
        $this->user->role = $this->selectedRole;
        $this->user->pay  = Rates::whereRole($this->user->role)->first()->rate ?? $this->user->pay;

        $this->showWarningRoleModal = false;
    }

    public function cleanSelectedManagers()
    {
        $this->selectedManagers = collect();
    }

    public function getManagersProperty()
    {
        if ($this->selectedRole === Role::DEPARTMENT_MANAGER) {
            return Department::query()
                ->where('id', $this->user->department_id)
                ->get();
        }

        if ($this->selectedRole === Role::OFFICE_MANAGER) {
            return Office::query()
                ->whereHas('region.department',
                    fn($query) => $query->where('departments.id', $this->user->department_id)
                )
                ->get();
        }

        if ($this->selectedRole === Role::REGION_MANAGER) {
            return Region::query()
                ->where('department_id', $this->user->department_id)
                ->get();
        }

        return collect();
    }

    private function detachOffices(User $user)
    {
        $user->managedOffices()->detach(
            $user->managedOffices()->select('offices.id')->pluck('id')->toArray()
        );
    }

    private function detachDepartments(User $user)
    {
        $user->managedDepartments()->detach(
            $user->managedDepartments()->select('departments.id')->pluck('id')->toArray()
        );
    }

    private function detachRegions(User $user)
    {
        $user->managedRegions()->detach(
            $user->managedRegions()->select('regions.id')->pluck('id')->toArray()
        );
    }

    public function teamLabel($team)
    {
        return match (get_class($team)) {
            Office::class     => 'Office',
            Region::class     => 'Region',
            Department::class => 'Department',
        };
    }

    public function updateOrgAssigment()
    {
        $rules = [
            'selectedRole' => 'required|in:' . implode(',', User::getRoleByNames()),
        ];

        if ($this->selectedRole === Role::REGION_MANAGER) {
            $rules = array_merge($rules, [
                'selectedManagers'   => 'required|array',
                'selectedManagers.*' => 'exists:regions,id',
            ]);
        }

        if ($this->selectedRole === Role::DEPARTMENT_MANAGER) {
            $rules = array_merge($rules, [
                'selectedManagers'   => 'required|array',
                'selectedManagers.*' => 'exists:departments,id',
            ]);
        }

        if ($this->selectedRole === Role::OFFICE_MANAGER) {
            $rules = array_merge($rules, [
                'selectedManagers'   => 'required|array',
                'selectedManagers.*' => 'exists:offices,id',
            ]);
        }

        $this->validate($rules);

        DB::transaction(function () {
            $data = ['role' => $this->selectedRole];

            if ($this->reportsTo !== null && $this->reportsTo !== 0) {
                $data['office_id'] = $this->reportsTo;
            }

            $this->user->update($data);

            $this->detachOffices($this->user);
            $this->detachDepartments($this->user);
            $this->detachRegions($this->user);

            if ($this->selectedRole === Role::OFFICE_MANAGER) {
                $this->user->managedOffices()->attach($this->selectedManagers);
            }

            if ($this->selectedRole === Role::DEPARTMENT_MANAGER) {
                $this->user->managedDepartments()->attach($this->selectedManagers);
            }

            if ($this->selectedRole === Role::REGION_MANAGER) {
                $this->user->managedRegions()->attach($this->selectedManagers);
            }
        });

        alert()
            ->withTitle(__('User has been updated!'))
            ->send();

        return redirect(
            route('castle.users.show', ['user' => $this->user->id, 'openedTab' => 'orgInfo'])
        );
    }

    private function getOfficeId($user)
    {
        $officeId = $user->office_id;

        if ($officeId === null && Department::where('id', $user->department_id)->exists()) {
            /** @var Department|null $department */
            $department = Department::find($user->department_id);

            if ($department !== null && $department->offices->isNotEmpty()) {
                $officeId = $department->offices->first()->id;
            }
        }

        return $officeId;
    }
}