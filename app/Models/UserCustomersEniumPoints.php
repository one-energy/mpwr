<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class UserCustomersEniumPoints extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['customer_id', 'user_sales_rep_id', 'points', 'set_date', 'expiration_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_sales_rep_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeInPeriod(Builder $query)
    {
        return $query->whereDate('set_date', '<=', Carbon::now())
            ->whereDate('expiration_date', '>=', Carbon::now());
    }
}
