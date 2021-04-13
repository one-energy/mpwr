<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\Invitation
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $token
 * @property string $email
 * @property int $master
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Invitation query()
 * @mixin \Eloquent
 */
class Invitation extends Model
{
    use HasFactory;
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
