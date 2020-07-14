<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\MasterExistingUserInvitation;
use App\Notifications\MasterInvitation;
use App\Rules\Castle\MasterEmailUnique;
use App\Rules\Castle\MasterEmailYourSelf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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
        return view('castle.users.register');
    }

    public function store()
    {
        $data = Validator::make(request()->all(), [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name'  => ['nullable', 'string', 'max:255'],
            'role'       => ['nullable', 'string', 'max:255'],
            'office'     => ['nullable', 'string', 'max:255'],
            'pay'        => ['nullable', 'numeric'],
            'email'      => ['required', 'email', 'unique:invitations', new MasterEmailUnique, new MasterEmailYourSelf, 'unique:users,email'],
        ], [
            'email.unique' => __('There is a pending invitation for this email.'),
        ])->validate();

        $user = $this->findUser($data['email']);

        $invitation          = new Invitation();
        $invitation->email   = $data['email'];
        $invitation->token   = Uuid::uuid4();
        $invitation->master  = false;
        $invitation->user_id = optional($user)->id;
        $invitation->save();

        $this->createUser($data, $invitation);

        $user ? $user->notify(new MasterExistingUserInvitation) : $invitation->notify(new MasterInvitation);

        return back()->with('message', __("The invitation was sent to {$data['email']}"));
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
        $user->office            = $data['office'];
        $user->pay               = $data['pay'];
        $user->save();

        return $user;
    }
}
