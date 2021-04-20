<?php

namespace App\Policies;

use App\Models\TrainingPageContent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPageContentPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, TrainingPageContent $trainingPageContent)
    {
        if ($user->role === 'Admin') {
            return true;
        }

        if ($user->role !== 'Department Manager' || $user->department_id === null) {
            return false;
        }

        if ($user->department->id !== $trainingPageContent->section->department_id) {
            return false;
        }

        return true;
    }
}
