<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $office_manager_id
 * @property int $region_id
 * @property Carbon $created_at
 * @property Carbon $deleted_at
 * @property-read User $owner
 */
class Office extends Model
{
    public function officeManger()
    {
        return $this->belongsTo(User::class, 'office_manager_id');
    }

    public function region()
    {
        return $this->belongsTo(User::class, 'region_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }
}
