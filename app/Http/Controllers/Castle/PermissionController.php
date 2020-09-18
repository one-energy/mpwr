<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\User;

class PermissionController extends Controller
{
    public function index()
    {
        return view('castle.permission.index');
    }

    public function edit(User $user)
    {
        $roles = User::ROLES;

        return view('castle.permission.edit', [
            'user'  => $user,
            'roles' => $roles,
        ]);
    }

    public function update($id)
    {
        $validated = $this->validate(
            request(),
            [
                'role' => 'nullable',
            ]
        );

        $user = User::find($id);

        $user->role = $validated['role'];

        if ($user->role == 'Admin' || $user->role == 'Owner') {
            $user->beCastleMaster();
        } else {
            $user->revokeMastersAccess();
        }
        
        $user->save();

        alert()
            ->withTitle(__('Permission has been updated!'))
            ->send();

        return redirect(route('castle.permission.index'));
    }
}
