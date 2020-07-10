<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    const CUSTOMERS = [
        ['id' => 1, 'name' => 'Donna Walker',   'price' => 4200, 'kw' => 6.2],
        ['id' => 2, 'name' => 'Chris Williams', 'price' => 4200, 'kw' => 6.2]
    ];
}
