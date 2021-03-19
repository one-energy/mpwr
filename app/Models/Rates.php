<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Rates
 *
 * @property int $id
 * @property string $name
 * @property int $time
 * @property int|null $department_id
 * @property string $role
 * @property string|null $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Department|null $department
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rates query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rates search($search)
 * @mixin \Eloquent
 */
class Rates extends Model
{
    use HasFactory;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function alreadyExists()
    {
        return Rates::where('id', '!=', $this->id)->whereRole($this->role)->whereDepartmentId($this->department_id)->where('time', $this->time)->get()->count();
    }

    public function scopeSearch(Builder $query, $search)
    {
        $query->when($search, function (Builder $query) use ($search) {
            $query->where(
                DB::raw('lower(rates.name)'),
                'like',
                '%' . strtolower($search) . '%'
            );
        });
    }
}
