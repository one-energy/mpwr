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
        if ($user->hasAnyRole(['Admin', 'Owner'])) {
            return true;
        }

        if ($user->hasRole('Region Manager') && $section !== null) {
            return TrainingPageSection::query()
                ->sectionsUserManaged($user)
                ->where('id', $section->id)
                ->exists();
        }

        return $user->department_id == $departmentId;
    }

    public function uploadSectionFile() {
        if (user()->notHaveRoles([Role::ADMIN, Role::OWNER, Role::DEPARTMENT_MANAGER])) {
            return false;
        }

        return true;
    }
}
