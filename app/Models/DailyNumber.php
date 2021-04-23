<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\DailyNumber
 *
 * @property int $id
 * @property int $user_id
 * @property string $date
 * @property int|null $doors
 * @property string|null $hours
 * @property int|null $sets
 * @property int|null $set_sits
 * @property int|null $sits
 * @property int|null $set_closes
 * @property int|null $closes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DailyNumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DailyNumber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DailyNumber query()
 * @method static \Illuminate\Database\Eloquent\Builder inPeriod(string $period, \Illuminate\Support\Carbon $date)
 * @mixin \Eloquent
 */
class DailyNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'doors',
        'hours',
        'sets',
        'set_sits',
        'sits',
        'set_closes',
        'closes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeInPeriod(Builder $query, string $period, Carbon $date)
    {
        if ($period === 'w') {
            $clonedDate = clone $date;
            $query->whereBetween('date', [$date->startOfWeek(), $clonedDate->endOfWeek()]);
        }

        if ($period === 'd') {
            $query->whereDate('date', $date);
        }

        if ($period === 'm') {
            $query->whereMonth('date', '=', $date);
        }

        return $query;
    }
}
