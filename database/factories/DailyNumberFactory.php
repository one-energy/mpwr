<?php

namespace Database\Factories;

use App\Models\DailyNumber;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class DailyNumberFactory extends Factory
{
    protected $model = DailyNumber::class;

    public function definition()
    {
        return [
            'user_id'    => null,
            'date'       => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'doors'      => rand(1, 100),
            'hours'      => rand(1, 100),
            'sets'       => rand(1, 100),
            'sits'       => rand(1, 100),
            'set_closes' => rand(1, 100),
            'closes'     => rand(1, 100)
        ];
    }
}
