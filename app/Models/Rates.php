<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $role
 * @property int $time
 * @property decimal $rate
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Department $department
 */
class Rates extends Model
{
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function ratesPerRole($role)
    {
        return Rates::whereRole($role)->first();
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
