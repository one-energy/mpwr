<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


/**
 * @property int $id
 * @property string $name
 * @property int $department_manager_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $departmentManager
 */
class Department extends Model
{
    public function departmentAdmin()
    {
        return $this->belongsTo(User::class, 'department_manager_id');
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where(
                DB::raw('lower(departments.name)'),
                'like',
                '%' . strtolower($search) . '%'
            );
        });
    }
}
