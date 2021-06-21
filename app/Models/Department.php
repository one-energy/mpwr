<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Department
 *
 * @property int $id
 * @property int|null $department_manager_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $departmentAdmin
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Office[] $offices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Region[] $regions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TrainingPageSection[] $trainingPageSections
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Department query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Department search($search)
 * @mixin \Eloquent
 */
class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_manager_id',
        'name',
    ];

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

    public function offices()
    {
        return $this->hasManyThrough(Office::class, Region::class);
    }

    public function officesTrashedParents()
    {
        return $this->hasManyThrough(Office::class, Region::class)->withTrashedParents();
    }

    public function trainingPageSections()
    {
        return $this->hasMany(TrainingPageSection::class);
    }

    public function incentives()
    {
        return $this->hasMany(Incentive::class);
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
