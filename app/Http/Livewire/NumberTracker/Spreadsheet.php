<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * @property-read Collection|Office[] $offices
 * @property-read array $periods
 * @property-read string $dateFormat
 */
class Spreadsheet extends Component
{
    public int $selectedOffice;

    public function mount()
    {
        $this->selectedOffice = $this->offices->first()->id;
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

    public function getWeeklyLabelsProperty()
    {
        $interval = new DateInterval('P1D');

        return collect($this->periods)
            ->chunk(2)
            ->map(fn($chunks) => collect($chunks)->values()->toArray())
            ->map(function ($chunk) use ($interval) {
                $days = [$chunk[0]->format($this->dateFormat)];

                $weekDays = new DatePeriod($chunk[0], $interval, $chunk[1], DatePeriod::EXCLUDE_START_DATE);

                foreach ($weekDays as $day) {
                    $days[] = $day->format($this->dateFormat);
                }

                $days[] = $chunk[1]->format($this->dateFormat);

                return $days;
            });
    }

    public function getPeriodsLabelProperty()
    {
        return collect($this->periods)
            ->chunk(2)
            ->map(fn($chunks) => collect($chunks)->values()->toArray())
            ->map(fn($chunks) => $chunks[0]->format($this->dateFormat) . ' - ' . $chunks[1]->format($this->dateFormat));
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
