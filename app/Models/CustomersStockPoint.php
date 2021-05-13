<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomersStockPoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stock_recruiter',
        'stock_setting',
        'stock_personal_sale',
        'stock_pod_leader_team',
        'stock_manager',
        'stock_divisional',
        'stock_regional',
        'stock_department',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
