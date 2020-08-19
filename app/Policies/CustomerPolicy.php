<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewList(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        if (in_array($user->role, User::TOPLEVEL_ROLES)) {
            return true;
        }

        return false;
    }

    public function view(User $user)
    {
        return true;
    }

    public function update(User $user)
    {
        if (in_array($user->role, User::TOPLEVEL_ROLES)) {
            return true;
        }

        return false;
    }

    public function delete(User $user)
    {
        if (in_array($user->role, User::TOPLEVEL_ROLES)) {
            return true;
        }

        return false;
    }
}