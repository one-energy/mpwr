<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'team'     => ['required', 'string', 'min:3', 'max:255'],
            'name'     => ['required', 'string', 'min:3', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:128', 'unique:users', 'confirmed'],
            'password' => ['required', 'string', 'min:8', 'max:128'],
        ]);
    }

    protected function create(array $data)
    {
        $user           = new User();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->save();

        $team           = new Team();
        $team->name     = $data['team'];
        $team->owner_id = $user->id;
        $team->save();

        $team->users()->attach($user, ['role' => 'owner']);


        return $user;
    }
}
