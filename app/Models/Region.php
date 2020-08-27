<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


/**
 * @property int $id
 * @property string $name
 * @property int $region_manager_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $regionManager
 */
class Region extends Model
{
    public function regionManger()
    {
        return $this->belongsTo(User::class, 'region_manager_id');
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
