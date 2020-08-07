<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IncentivePolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if (in_array($user->role, User::TOPLEVEL_ROLES)) {
            return true;
        }

        return false;
    }

    public function viewList(User $user)
    {
        if (in_array($user->role, User::TOPLEVEL_ROLES)) {
            return true;
        }

        return false;
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
        if (in_array($user->role, User::TOPLEVEL_ROLES)) {
            return true;
        }

        return false;
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