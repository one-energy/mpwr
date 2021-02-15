<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Invitation;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\Notifications\UserInvitation;
use App\Rules\Castle\MasterEmailUnique;
use App\Rules\Castle\MasterEmailYourSelf;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

class UsersController extends Controller
{
    public function index()
    {
        return view('castle.users.index');
    }

    public function show(User $user)
    {
        return view('castle.users.show', compact('user'));
    }

    public function create()
    {
        return view('castle.users.register', [
            'roles'       => User::getRolesPerUserRole(),
            'departments' => Department::all(),
        ]);
    }

    public function store()
    {
        $data = request()->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'role'          => ['nullable', 'string', 'max:255'],
            'office_id'     => ['nullable'],
            'pay'           => ['nullable'],
            'department_id' => ['nullable'],
            'email'         => ['required', 'email', 'unique:invitations', new MasterEmailUnique, new MasterEmailYourSelf, 'unique:users,email'],
        ], [
            'email.unique' => __('There is a pending invitation for this email.'),
        ]);

        $user = User::query()->where('email', '=', $data['email'])->first();

        $invitation        = new Invitation();
        $invitation->email = $data['email'];
        $invitation->token = Uuid::uuid4();

        if ($data['office_id'] == 'None') {
            $data['office_id'] = null;
        }
        if ($data['department_id'] == 'None') {
            $data['department_id'] = null;
        }
        if ($data['role'] == 'Admin' || $data['role'] == 'Owner') {
            $invitation->master    = true;
            $data['department_id'] = null;
        } else {
            $invitation->master = false;
        }

        $invitation->user_id = optional($user)->id;
        $invitation->save();

        $this->createUser($data, $invitation);

        $invitation->notify(new UserInvitation);

        return back()->with('message', __("The invitation was sent to {$data['email']}"));
    }

    public function edit(User $user)
    {
        $departments = Department::all();
        $roles       = User::getRolesPerUserRole();
        $offices     = $this->getOfficesPerRole();

        return view('castle.users.edit', [
            'user'        => $user,
            'roles'       => $roles,
            'offices'     => $offices,
            'departments' => $departments,
        ]);
    }

    public function destroy($id)
    {
        if ($id == auth()->user()->id) {
            alert()
                ->withTitle(__('You cannot delete yourself!'))
                ->send();

            return back();
        }

        $user      = User::find($id);
        $canDelete = User::userCanChangeRole($user);
        if ($canDelete['status']) {
            User::destroy($id);
        } else {
            alert()
                ->withTitle(__('You cannot delete this user!'))
                ->withDescription(__('This user was associate to any department, region or office. Please desassociate this user before continue'))
                ->send();

            return back();
        }

        alert()
            ->withTitle(__('User has been deleted!'))
            ->send();

        return redirect(route('castle.users.index'));
    }

    public function getOfficesPerRole()
    {
        $officesQuery = Office::query()->select('offices.*');
        if (user()->role == 'Department Manager') {
            $offices = $officesQuery
                ->join('regions', 'offices.region_id', '=', 'regions.id')
                ->where('regions.department_id', '=', user()->department_id)->get();
        }
        if (user()->role == 'Region Manager') {
            $offices = $officesQuery
                ->select('offices.name', 'offices.id')
                ->join('regions', function ($join) {
                    $join->on('offices.region_id', '=', 'regions.id')
                        ->where('regions.region_manager_id', '=', user()->id);
                })->get();
        }
        if (user()->role == 'Office Manager') {
            $offices = $officesQuery
                ->whereOfficeManagerId(user()->id);
        }

        if (user()->role == 'Admin' || user()->role == 'Owner') {
            $offices = Office::all();
        }

        return $offices;
    }

    private function createUser(array $data, Invitation $invitation): User
    {
        $user                    = new User();
        $user->first_name        = $data['first_name'];
        $user->last_name         = $data['last_name'];
        $user->email             = $data['email'];
        $user->password          = bcrypt(Str::random(8));
        $user->email_verified_at = null;
        $user->master            = $invitation->master;
        $user->role              = $data['role'];
        $user->office_id         = $data['office_id'];
        $user->department_id     = $data['department_id'];
        $user->pay               = $data['pay'];
        $user->save();

        return $user;
    }

    public function requestResetPassword(User $user)
    {
        return view('castle.users.reset-password', compact('user'));
    }

    public function resetPassword($id)
    {
        $user = User::find($id);

        $data = request()->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user
            ->changePassword($data['new_password'])
            ->save();

        alert()->withTitle(__('Password reset successfully!'))->send();

        return redirect(route('castle.users.edit', compact('user')));
    }

    public function getUsers()
    {
        return User::get();
    }

    public function update($id)
    {
        $data = request()->validate([
            'first_name'    => ['required', 'string', 'min:3', 'max:255'],
            'last_name'     => ['required', 'string', 'min:3', 'max:255'],
            'role'          => ['nullable', 'string', 'max:255'],
            'office_id'     => ['nullable', 'numeric'],
            'pay'           => ['nullable', 'numeric'],
            'department_id' => ['nullable', 'numeric'],
            'email'         => ['required', 'email', 'min:2', 'max:128', Rule::unique('users')->ignore($id)],
        ]);

        $user = User::find($id);
        $user->forceFill($data);

        $user->save();

        alert()
            ->withTitle(__('User has been updated!'))
            ->send();

        return redirect(route('castle.users.index'));
    }

    public function getRegionsManager($departmentId)
    {
        return User::whereDepartmentId($departmentId)
            ->whereRole('Region Manager')
            ->get();
    }

    public function getOfficesManager($regionId)
    {
        $region = Region::whereId($regionId)->first();

        $usersQuery = User::query()->select('users.*');

        return $usersQuery->whereRole('Office Manager')
            ->whereDepartmentId($region->department_id)
            ->get();
    }
}
