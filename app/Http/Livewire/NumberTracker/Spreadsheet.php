<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\User;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * @property-read Collection|Office[] $offices
 * @property-read array $periods
 * @property-read Collection $weeklyPeriods
 * @property-read string $dateFormat
 * @property-read Collection|DailyNumber[] $users
 */
class Spreadsheet extends Component
{
    public int $selectedOffice;

    public function mount()
    {
        $this->selectedOffice = match (user()->role) {
            'Admin', 'Owner' => $this->offices->first()->id,
        };
    }

    public function render()
    {
        return view('livewire.number-tracker.spreadsheet');
    }

    public function getPeriodsProperty()
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

    public function getWeeklyPeriodsProperty()
    {
        $interval = new DateInterval('P1D');

        return collect($this->periods)
            ->chunk(2)
            ->map(function (Collection $chunk) use ($interval) {
                $days = [$chunk->first()];

                $weekDays = new DatePeriod($chunk->first(), $interval, $chunk->last(), DatePeriod::EXCLUDE_START_DATE);

                foreach ($weekDays as $day) {
                    $days[] = $day;
                }

                $days[] = $chunk->last();

                return $days;
            });
    }

    public function getWeeklyLabelsProperty()
    {
        return collect($this->weeklyPeriods)
            ->map(function (array $periods) {
                return collect($periods)
                    ->map(fn(DateTimeImmutable $date) => $date->format($this->dateFormat));
            });
    }

    public function getPeriodsLabelProperty()
    {
        return collect($this->periods)
            ->chunk(2)
            ->map(function ($chunks) {
                return $chunks->first()->format($this->dateFormat) . ' - ' . $chunks->last()->format($this->dateFormat);
            });
    }

    public function getUsersProperty()
    {
        return User::query()
            ->where('office_id', $this->selectedOffice)
            ->with([
                'dailyNumbers' => function ($query) {
                    $query->orderBy('date', 'asc');
                },
            ])
            ->whereHas('dailyNumbers', function ($query) {
                $query->where('office_id', $this->selectedOffice);
            })
            ->get()
            ->map(function (User $user) {
                $user->dailyNumbers = $user->dailyNumbers->groupBy(function (DailyNumber $dailyNumber) {
                    return (new Carbon($dailyNumber->date))->format('F dS');
                });

                return $user;
            });
    }

    public function getDateFormatProperty()
    {
        return 'F dS';
    }

    public function getIndicatorsProperty()
    {
        return [
            ['label' => 'HW', 'description' => 'Hours Worked'],
            ['label' => 'D', 'description' => 'Doors'],
            ['label' => 'HK', 'description' => 'Hours Knocked'],
            ['label' => 'S', 'description' => 'Sets'],
            ['label' => 'SA', 'description' => 'Sats'],
            ['label' => 'SC', 'description' => 'Set Closes'],
            ['label' => 'CS', 'description' => 'Closer Sits'],
            ['label' => 'C', 'description' => 'Closes'],
        ];
    }

    public function getIsAdminProperty()
    {
        return user()->hasAnyRole(['Admin', 'Owner']);
    }

    public function getOfficesProperty()
    {
        return Office::get();
    }
}
