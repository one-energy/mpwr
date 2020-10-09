<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Invitation;
use App\Models\Office;
use App\Models\User;
use App\Notifications\MasterExistingUserInvitation;
use App\Notifications\MasterInvitation;
use App\Rules\Castle\MasterEmailUnique;
use App\Rules\Castle\MasterEmailYourSelf;
use Illuminate\Support\Facades\Validator;
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
        $departments = Department::all();
        $roles = $this->getRolesPerRole();
        $offices = $this->getOfficesPerRole();

        return view('castle.users.register',[
            'roles'   => $roles,
            'offices' => $offices,
            'departments' => $departments,
        ]);
    }

    public function store()
    {
        $data = Validator::make(request()->all(), [
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'role'          => ['nullable', 'string', 'max:255'],
            'office_id'     => ['nullable'],
            'pay'           => ['nullable'],
            'department_id' => ['nullable'],
            'email'         => ['required', 'email', 'unique:invitations', new MasterEmailUnique, new MasterEmailYourSelf, 'unique:users,email'],
        ], [
            'email.unique' => __('There is a pending invitation for this email.'),
        ])->validate();

        $user = $this->findUser($data['email']);

        $invitation          = new Invitation();
        $invitation->email   = $data['email'];
        $invitation->token   = Uuid::uuid4();

        if($data["office_id"] == "None"){
            $data["office_id"] = null;
        }
        if($data["department_id"] == "None"){
            $data["department_id"] = null;
        }
        if ($data['role'] == 'Admin' || $data['role'] == 'Owner') {
            $invitation->master    = true;
            $data['department_id'] = null;
        } else {
            $invitation->master  = false;
        }

        $invitation->user_id = optional($user)->id;
        $invitation->save();

        $this->createUser($data, $invitation);

        $user ? $user->notify(new MasterExistingUserInvitation) : $invitation->notify(new MasterInvitation);

        return back()->with('message', __("The invitation was sent to {$data['email']}"));
    }


    public function edit(User $user)
    {
        
        $departments = Department::all();
        $roles = $this->getRolesPerRole();
        $offices = $this->getOfficesPerRole();
        return view('castle.users.edit', [
            'user'    => $user,
            'roles'   => $roles,
            'offices' => $offices,
            'departments' => $departments,
        ]);
    }

    public function getOfficesPerRole()
    {
        $officesQuery = Office::query()->select("offices.*");
        if(user()->role == "Department Manager"){
            $offices = $officesQuery
                ->join('regions', 'offices.region_id', '=', 'regions.id')
                ->where('regions.department_id', '=', user()->department_id)->get();
        }
        if(user()->role == "Region Manager"){
            $offices = $officesQuery
                ->select('offices.name', 'offices.id')
                ->join('regions', function($join){
                    $join->on('offices.region_id', '=', 'regions.id')
                        ->where('regions.region_manager_id', '=', user()->id);
                })->get();
        }
        if(user()->role == "Office Manager"){
            $offices = $officesQuery
                ->whereOfficeManagerId(user()->id);
        }

        if(user()->role == "Admin" || user()->role == "Owner"){
            $offices = Office::all();
        }
        return $offices;
    }

    public function getRolesPerRole()
    {
        if(user()->role == "Admin"){
            $roles = [
                ['name' => 'Admin',              'description' => 'Allows to update al system except owner users'],
                ['name' => 'Department Manager', 'description' => 'Allows update all in departments and Regon\'s Number Traker'],
                ['name' => 'Region Manager',     'description' => 'Allows update all Regon\'s Number Traker'],
                ['name' => 'Office Manager',     'description' => 'Allows update a Region\'s Number Tracker'],
                ['name' => 'Sales Rep',          'description' => 'Allows read/add/edit/cancel Customer'],
                ['name' => 'Setter',             'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }
        if(user()->role == "Department Manager"){
            $roles = [
                ['name' => 'Region Manager',     'description' => 'Allows update all Regon\'s Number Traker'],
                ['name' => 'Office Manager',     'description' => 'Allows update a Region\'s Number Tracker'],
                ['name' => 'Sales Rep',          'description' => 'Allows read/add/edit/cancel Customer'],
                ['name' => 'Setter',             'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }
        if(user()->role == "Region Manager"){
            $roles = [
                ['name' => 'Office Manager',     'description' => 'Allows update a Region\'s Number Tracker'],
                ['name' => 'Sales Rep',          'description' => 'Allows read/add/edit/cancel Customer'],
                ['name' => 'Setter',             'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }
        if(user()->role == "Office Manager"){
            $roles = [
                ['name' => 'Sales Rep',          'description' => 'Allows read/add/edit/cancel Customer'],
                ['name' => 'Setter',             'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }

        if(user()->role == "Owner"){
            $roles   = User::ROLES;
        }
        return $roles;
    }

    public function update($id)
    {
        $data = Validator::make(request()->all(), [
            'first_name'    => ['required', 'string', 'min:3', 'max:255'],
            'last_name'     => ['required', 'string', 'min:3', 'max:255'],
            'role'          => ['nullable', 'string', 'max:255'],
            'office_id'     => ['nullable', 'numeric'],
            'pay'           => ['nullable', 'numeric'],
            'department_id' => ['nullable', 'numeric'],
            'email'         => ['required', 'email', 'min:2', 'max:128', Rule::unique('users')->ignore($id)],
        ])->validate();
        
        $user = User::find($id);
        $user->forceFill($data);

        if ($data['role'] == 'Admin' || $data['role'] == 'Owner') {
            $user->beCastleMaster();
            $data['department_id'] = null;
            
        } else {
            $user->revokeMastersAccess();
        }

        $user->save();

        alert()
            ->withTitle(__('User has been updated!'))
            ->send();

        return redirect(route('castle.users.index'));
    }

    public function requestResetPassword(User $user)
    {
        return view('castle.users.reset-password', compact('user'));
    }

    public function resetPassword($id)
    {
        $user = User::find($id);

        $data = Validator::make(request()->all(), [
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        $user
            ->changePassword($data['new_password'])
            ->save();

        alert()->withTitle(__('Password reset successfully!'))->send();

        return redirect(route('castle.users.edit', compact('user')));
    }

    public function destroy($id)
    {
        if ($id == auth()->user()->id) {
            alert()
                ->withTitle(__('You cannot delete yourself!'))
                ->send();

            return back();
        }

        User::destroy($id);

        alert()
            ->withTitle(__('User has been deleted!'))
            ->send();

        return redirect(route('castle.users.index'));
    }

    /**
     * @param $email
     * @return User|null
     */
    private function findUser($email)
    {
        /** @var User $user */
        if ($user = User::query()->where('email', '=', $email)->first()) {
            return $user;
        }

        return null;
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
}
