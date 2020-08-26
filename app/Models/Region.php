<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * @property int $id
 * @property string $name
 * @property int $owner_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $owner
 */
class Region extends Model
{
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where(
                DB::raw('lower(regions.name)'),
                'like',
                '%' . strtolower($search) . '%'
            )
            ->orWhere(
                DB::raw('lower(users.first_name)'),
                'like',
                '%' . strtolower($search) . '%'
            )
            ->orWhere(
                DB::raw('lower(users.last_name)'),
                'like',
                '%' . strtolower($search) . '%'
            );
        });
    }
}
