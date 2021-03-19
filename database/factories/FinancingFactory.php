<?php

namespace Database\Factories;

use App\Models\Financing;
use Illuminate\Database\Eloquent\Factories\Factory;
class FinancingFactory extends Factory
{
    protected $model = Financing::class;
    public function definition()
    {
        return [
            'name' => 'Purchase'
        ];
    }
}
