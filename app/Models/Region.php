<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Region
 *
 * @property int $id
 * @property int $region_manager_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $department_id
 * @property-read \App\Models\Department|null $department
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Office[] $offices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $managers
 * @property-read \App\Models\User $regionManager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TrainingPageSection[] $trainingPageSections
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Region search($search)
 * @mixin \Eloquent
 */
class Region extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'department_id',
    ];

    public function managers()
    {
        return $this->belongsToMany(User::class, 'user_managed_regions')->withTimestamps();
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function offices()
    {
        return $this->hasMany(Office::class);
    }

    public function trainingPageSections()
    {
        return $this->hasMany(TrainingPageSection::class);
    }

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where(
                DB::raw('lower(regions.name)'),
                'like',
                '%' . strtolower($search) . '%'
            );
        });
    }
}
