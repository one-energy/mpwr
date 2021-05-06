<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomersEniumPoints extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_sales_rep_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
