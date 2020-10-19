<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewList(User $user, ?int $department_id)
    {
        if($user->role == "Admin" || $user->role == "Owner"){
            return true;
        }
        return $user->department_id == $department_id;
    }
}
