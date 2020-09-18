<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property int $office_manager_id
 * @property int $region_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read User $owner
 */
class Office extends Model
{
    use SoftDeletes;

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
        return $this->hasOne(User::class);
    }

    public function getOfficeManagerAttribute()
    {
        return User::find($this->office_manager_id);
    }

    public function getRegionAttribute()
    {
        return Region::find($this->region_id);
    }

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where(
                DB::raw('lower(offices.name)'),
                'like',
                '%' . strtolower($search) . '%'
            )
            ->orWhere(
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
