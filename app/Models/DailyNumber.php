<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyNumber extends Model
{
    protected $fillable = ['user_id', 'date', 'doors', 'hours', 'sets', 'sits', 'set_closes', 'closes'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
