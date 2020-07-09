<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\User;

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
}
