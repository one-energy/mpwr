<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'userLevel'       => user()->level() ?? null,
            'userEniumPoints' => user()->eniumPoints() ?? 0,
            'stockPoints'     => user()->stockPoints()
        ]);
    }

    public function showData(User $user)
    {
        return view('profile.show-data', compact('user'));
    }

    public function show()
    {
        return view('profile.show');
    }

    public function update()
    {
        $data = Validator::make(request()->all(), [
            'first_name'  => ['required', 'min:3', 'max:255'],
            'last_name'   => ['required', 'min:3', 'max:255'],
            'email'       => ['required', 'email', 'min:2', 'max:128', Rule::unique('users')->ignore(user()->id)],
            'photo_url'   => ['nullable'],
        ])->validate();

        user()
            ->forceFill($data)
            ->save();

        alert()
            ->withTitle(__('Your profile has been updated!'))
            ->send();

        return back();
    }
}
