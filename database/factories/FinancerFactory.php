<?php

namespace Database\Factories;

use App\Models\Financer;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancerFactory extends Factory
{
    protected $model = Financer::class;

    public function definition()
    {
        return [
            'name' => 'Enium',
        ];
    }
}
