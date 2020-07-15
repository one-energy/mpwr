<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class Customer extends Model
{
    const CUSTOMERS = [
        ['id' => 1, 'name' => 'Donna Walker',   'price' => 4200, 'kw' => 6.2],
        ['id' => 2, 'name' => 'Chris Williams', 'price' => 4200, 'kw' => 6.2]
    ];
}
