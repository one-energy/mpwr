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
        $hoursKnocked = Arr::random(range(1, 10));
        $closes       = Arr::random(range(1, 10));
        $closerSits   = Arr::random(range(1, 10));
        $hoursWorked  = $hoursKnocked + $closes + ($closerSits * 2);

        return [
            'user_id'       => null,
            'office_id'     => null,
            'date'          => $this->faker->date('Y-m-d', 'now'),
            'doors'         => rand(1, 100),
            'hours'         => Arr::random(range(1, 24)),
            'sets'          => rand(1, 100),
            'sits'          => rand(1, 100),
            'set_closes'    => rand(1, 100),
            'sats'          => Arr::random(range(1, 100)),
            'hours_worked'  => $hoursWorked,
            'hours_knocked' => $hoursKnocked,
            'closer_sits'   => $closerSits,
            'closes'        => $closes,
        ];
    }
}
