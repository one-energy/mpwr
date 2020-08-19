<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NumberTrackingPolicy
{
    use HandlesAuthorization;

    public function viewList(User $user)
    {
        return true;
    }

    public function update(User $user)
    {
        if ($user->role == 'Setter' || $user->role == 'Sales Rep') {
            return false;
        }elseif($user->role == 'Office Manager'){

        }else{
            return true;
        }
    }
}