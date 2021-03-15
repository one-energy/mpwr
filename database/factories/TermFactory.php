<?php

namespace Database\Factories;

use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class TermFactory extends Factory
{
    protected $model = Term::class;
    public function definition()
    {
        return [
            'value' => '25Y $15/mo. 3.99%'
        ];
    }
}
