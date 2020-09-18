<?php

namespace App\Policies;

use App\Models\Office;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyNumberPolicy
{
    use HandlesAuthorization;

    public function viewList(User $user)
    {
        return true;
    }

    public function update(User $user, int $office)
    {
        $office  = Office::find($office);
        $ownerId = $office->office_manager_id;

        if (($user->role == 'Setter' || $user->role == 'Sales Rep') || ($user->role == 'Office Manager' && $user->id != $ownerId)) {
            return false;
        }

        return true;
    }
}