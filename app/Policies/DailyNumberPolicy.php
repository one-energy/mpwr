<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DailyNumber;
use App\Models\Region;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyNumberPolicy
{
    use HandlesAuthorization;

    public function viewList(User $user)
    {
        return true;
    }

    public function update(User $user, int $region)
    {
        $region = Region::find($region);
        $ownerId = $region->region_manager_id;

        if (($user->role == 'Setter' || $user->role == 'Sales Rep') || ($user->role == 'Office Manager' && $user->id != $ownerId))
        {
            return false;
        }

        return true;
    }
}