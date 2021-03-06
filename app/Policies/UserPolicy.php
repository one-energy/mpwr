<?php

namespace App\Policies;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function show(User $auth, User $user)
    {
        if ($auth->hasAnyRole([Role::ADMIN, Role::OWNER])) {
            return true;
        }

        if (
            $auth->hasAnyRole([Role::DEPARTMENT_MANAGER, Role::REGION_MANAGER, Role::OFFICE_MANAGER]) &&
            $user->hasRole(Role::ADMIN)
        ) {
            return false;
        }

        if ($auth->hasRole(Role::DEPARTMENT_MANAGER)) {
            return (int)$user->department_id === (int)$auth->department_id;
        }

        if ($auth->hasRole(Role::REGION_MANAGER)) {
            return $user?->office?->region?->region_manager_id === $auth->id;
        }

        if ($auth->hasRole(Role::OFFICE_MANAGER)) {
            return $user?->office?->office_manager_id === $auth->id;
        }

        return false;
    }
}
