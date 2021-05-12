<?php

namespace Database\Seeders;

use App\Models\DailyNumber;
use App\Models\User;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DailyNumbersSeeder extends Seeder
{
    public function run()
    {
        User::query()
            ->where('role', 'Setter')
            ->each(function (User $user) {
                foreach ($this->weeklyPeriods() as $day) {
                    DailyNumber::factory()->create([
                        'user_id'   => $user->id,
                        'office_id' => $user->office_id,
                        'date'      => $day->format('Y-m-d')
                    ]);
                }
            });
    }

    private function periods()
    {
        $date = DateTimeImmutable::createFromMutable(today());

        $firsDayOfMonth = $date->modify('first day of this month');
        $lasDayOfMonth  = $date->modify('last day of this month');
        $interval       = new DateInterval('P7D');

        $period = new DatePeriod(
            $firsDayOfMonth,
            $interval,
            $lasDayOfMonth,
            DatePeriod::EXCLUDE_START_DATE
        );

        $weeks = [$firsDayOfMonth];

        foreach ($period as $date) {
            $weeks[] = $date->modify('-1 day');
            $weeks[] = $date;
        }

        $weeks[] = $lasDayOfMonth;

        return $weeks;
    }

    private function weeklyPeriods()
    {
        $interval = new DateInterval('P1D');

        return collect($this->periods())
            ->chunk(2)
            ->map(function (Collection $chunk) use ($interval) {
                $days = [$chunk->first()];

                $weekDays = new DatePeriod($chunk->first(), $interval, $chunk->last(), DatePeriod::EXCLUDE_START_DATE);

                foreach ($weekDays as $day) {
                    $days[] = $day;
                }

                $days[] = $chunk->last();

                return $days;
            })
            ->flatten();
    }
}
