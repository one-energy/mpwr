<?php

namespace Database\Factories;

use App\Models\DailyNumber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class DailyNumberFactory extends Factory
{
    protected $model = DailyNumber::class;

    public function definition()
    {
        return [
            'user_id'       => null,
            'office_id'     => null,
            'date'          => Arr::random([
                now(),
                now()->addDays(Arr::random(range(1, 10))),
                now()->subMonth(),
            ]),
            'doors'         => rand(1, 100),
            'hours'         => Arr::random(range(1, 24)),
            'sets'          => rand(1, 100),
            'sits'          => rand(1, 100),
            'set_closes'    => rand(1, 100),
            'hours_worked'  => Arr::random(range(1, 24)),
            'hours_knocked' => Arr::random(range(1, 24)),
            'sats'          => Arr::random(range(1, 100)),
            'closer_sits'   => Arr::random(range(1, 100)),
        ];
    }
}
