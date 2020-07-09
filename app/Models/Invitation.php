<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $token
 * @property string $email
 * @property boolean $master
 * @property Carbon $created_at
 * @property Carbon $deleted_at
 * @property int|null $user_id
 */
class Invitation extends Model
{
    use Notifiable;

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function path()
    {
        return route('register.with-invitation', $this);
    }
}
