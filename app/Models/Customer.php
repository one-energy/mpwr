<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $setter
 * @property string $pay
 * @property float $system_size
 * @property float $redline
 * @property float $bill
 * @property float $financing
 * @property float $adders
 * @property float $gross_ppw
 * @property float $comission
 * @property float $setter_fee
 * @property boolean $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class Customer extends Model
{
    protected $casts = [
        'is_active' => 'boolean',
    ];

    const BILLS = [
        'Domestic',
        'CARE',
        'FERA'
    ];

    const FINANCINGS = [
        'Purchase',
        'PPA',
        'PACE'
    ];

    public function userOpenedBy()
    {
        return $this->belongsTo(User::class, 'opened_by_id');
    }

    public function getOpenedByAttribute()
    {
        return User::find($this->opened_by_id);
    }
}
