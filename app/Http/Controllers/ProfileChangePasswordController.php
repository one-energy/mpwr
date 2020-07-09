<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileChangePasswordController extends Controller
{
    public function __invoke()
    {
        $user = user();

        $data = Validator::make(request()->all(), [
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (Hash::check($value, $user->password)) {
                    return;
                }
                $fail(__('validation.current_password', ['attribute' => str_replace('_', ' ', $attribute)]));
            }],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ])->validate();

        $user
            ->changePassword($data['new_password'])
            ->save();

        alert()->withTitle(__('Password changed successfully!'))->send();

        return back();
    }
}
