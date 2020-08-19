<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Region;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyNumberPolicy
{
    use HandlesAuthorization;

    public function viewList(User $user)
    {
        return true;
    }

    public function update(User $user, Region $region)
    {
        if (($user->role == 'Setter' || $user->role == 'Sales Rep')|| 
            ($user->role == 'Office Manager' && $user->regions != $region)) 
        {
            return false;
        }

        return true;
    }
}