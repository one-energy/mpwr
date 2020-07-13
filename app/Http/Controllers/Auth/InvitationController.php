<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function invite($token)
    {
        /** @var Invitation $invitation */
        $invitation = Invitation::query()->where('token', '=', $token)->firstOrFail();
        $email      = mask_email($invitation->email);

        if ($invitation->user_id) {
            return redirect()->route('home');
        }

        return view('auth.register-with-invitation', compact('invitation', 'email'));
    }

    public function register($token)
    {
        /** @var Invitation $invitation */
        $invitation = Invitation::query()->where('token', '=', $token)->firstOrFail();

        $data          = request()->all();
        $data['email'] = $invitation->email;

        Validator::make($data, [
            'email'    => ['confirmed'],
            'password' => ['required', 'string', 'min:8', 'max:128'],
        ])->validate();

        $user = User::where('email', $data['email'])->firstOrFail();
            
        $user->email_verified_at = now();
        $user->password          = bcrypt($data['password']);
        $user->save();

        $invitation->delete();

        auth()->login($user);

        return redirect()->route('home')
            ->with('message', __('Welcome to ') . config('app.name'));
    }
}
