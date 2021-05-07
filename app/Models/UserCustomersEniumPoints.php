<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCustomersEniumPoints extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['customer_id', 'user_sales_rep_id', 'points'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_sales_rep_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
