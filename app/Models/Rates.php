<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rates extends Model
{
    public function department()
    {
        return $this->belongsTo(Department::class);
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
