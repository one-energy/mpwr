<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Office
 *
 * @property int $id
 * @property string $name
 * @property int $region_id
 * @property int $office_manager_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\DailyNumber[] $dailyNumbers
 * @property-read \App\Models\User $officeManager
 * @property-read \App\Models\Region $region
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Office newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Office newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Office onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Office query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Office search($search)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Office withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Office withoutTrashed()
 * @mixin \Eloquent
 */
class Office extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function officeManager()
    {
        return $this->belongsTo(User::class, 'office_manager_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function dailyNumbers()
    {
        return $this->hasMany(DailyNumber::class);
    }

    public function departments()
    {
        return $this->hasManyThrough(Department::class, Region::class);
    }

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where(
                DB::raw('lower(offices.name)'),
                'like',
                '%' . strtolower($search) . '%'
            );
        });
    }
}
