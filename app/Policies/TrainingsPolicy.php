<?php

namespace App\Policies;

use App\Enum\Role;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingsPolicy
{
    use HandlesAuthorization;

    public function viewList(User $user, ?int $departmentId, TrainingPageSection $section = null)
    {
        if ($user->hasAnyRole([Role::ADMIN, Role::OWNER])) {
            return true;
        }

        if ($section !== null && $user->hasRole(Role::REGION_MANAGER)) {
            return TrainingPageSection::query()
                ->sectionsUserManaged($user)
                ->where('id', $section->id)
                ->exists();
        }

        return $user->department_id == $departmentId;
    }

    public function delete(User $user, TrainingPageSection $section)
    {
        if ($section->parent_id === null) {
            return false;
        }

        if ($user->hasRole(Role::ADMIN)) {
            return true;
        }

        if ($section !== null && $user->hasRole(Role::REGION_MANAGER)) {
            if ($section->isDepartmentSection()) {
                return false;
            }

            return TrainingPageSection::query()
                ->sectionsUserManaged($user)
                ->where('id', $section->id)
                ->exists();
        }

        return $section->department_id === $user->department_id;
    }
}
