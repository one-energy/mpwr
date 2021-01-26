<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\Rates;

class RateRepository
{
    public function getRatesPerRole($role)
    {
        return Rates::whereRole($role)->first();
    }
}
