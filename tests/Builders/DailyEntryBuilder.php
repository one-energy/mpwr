<?php

namespace Tests\Builders;

use App\Models\DailyNumber;
use Illuminate\Foundation\Testing\WithFaker;

class DailyEntryBuilder
{
    use WithFaker;

    /** @var DailyNumber */
    public $dailyNumber;

    public function __construct($attributes = [])
    {
        $this->faker        = $this->makeFaker('en_US');
        $this->dailyNumber  = (new DailyNumber)->forceFill(array_merge([
            'date'       => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'doors'      => rand(1, 100),
            'hours'      => rand(1, 100),
            'sets'       => rand(1, 100),
            'sits'       => rand(1, 100),
            'set_closes' => rand(1, 100),
            'closes'     => rand(1, 100),
        ], $attributes));
    }

    public static function build(array $attributes = []): self
    {
        return new DailyEntryBuilder($attributes);
    }

    public function save()
    {
        $this->dailyNumber->save();

        return $this;
    }

    public function get()
    {
        return $this->dailyNumber;
    }

    public function withUser(int $id)
    {
        $this->dailyNumber->user_id = $id;

        return $this;
    }

    public function withDate(string $date)
    {
        $this->dailyNumber->date = $date;

        return $this;
    }
}
