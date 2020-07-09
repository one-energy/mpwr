<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property int $owner_id
 * @property string $photo_url
 * @property Carbon $created_at
 * @property Carbon $deleted_at
 * @property-read User $owner
 */
class Team extends Model
{
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }
}
