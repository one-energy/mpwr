<?php

namespace App\Models;

use App\Financer;
use App\Financing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $setter_id
 * @property string $pay
 * @property float $system_size
 * @property float $bill
 * @property float $financing
 * @property float $adders
 * @property float $epc
 * @property float $commission
 * @property float $setter_fee
 * @property boolean $panel_sold
 * @property boolean $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class Customer extends Model
{
    use SoftDeletes;

    protected $casts = [
        'panel_sold' => 'boolean',
        'is_active'  => 'boolean',
    ];

    const BILLS = [
        'Domestic',
        'CARE',
        'FERA',
    ];

    const FINANCINGS = [
        'Purchase',
        'PPA',
        'PACE',
    ];

    public function userOpenedBy()
    {
        return $this->belongsTo(User::class, 'opened_by_id');
    }

    public function userSetter()
    {
        return $this->belongsTo(User::class, 'setter_id');
    }

    public function financer()
    {
        return $this->hasOne(Financer::class);
    }

    public function financing()
    {
        return $this->hasOne(Financing::class);
    }

    public function term()
    {
        return $this->hasOne(Term::class);
    }

    public function getOpenedByAttribute()
    {
        return User::find($this->opened_by_id);
    }

    public function getSetterAttribute()
    {
        return User::find($this->setter_id);
    }
}
