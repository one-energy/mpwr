<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * @property-read Collection|Office[] $offices
 * @property-read array $periods
 * @property-read Collection $weeklyPeriods
 * @property-read Collection $weeklyMonthLabels
 * @property-read string $dateFormat
 * @property-read Collection|DailyNumber[] $users
 */
class Spreadsheet extends Component
{
    public int $selectedOffice;

    public Collection $dailyNumbers;

    public function mount()
    {
        $this->selectedOffice = $this->offices->isNotEmpty() ? $this->offices->first()->id : 0;
        $this->dailyNumbers   = $this->users->pluck('dailyNumbers')->flatten();
    }

    public function render()
    {
        return view('livewire.number-tracker.spreadsheet');
    }

    public function updatedSelectedOffice()
    {
        $this->dailyNumbers = $this->users->pluck('dailyNumbers')->flatten();
    }

    public function getPeriodsProperty()
    {
        $weeks = [];

        $currentWeek    = DateTimeImmutable::createFromMutable(today());
        $firstDayOfWeek = $currentWeek->modify(today()->startOfWeek());
        $lastDayOfWeek  = $currentWeek->modify(today()->endOfWeek()->subDay());

        $weeks[] = $firstDayOfWeek;
        $weeks[] = $lastDayOfWeek;

        $subWeeks = collect()->times(3)->map(function ($number) {
            $today       = DateTimeImmutable::createFromMutable(today());
            $currentWeek = today()->subWeeks($number);

            return [
                $today->modify($currentWeek->startOfWeek()),
                $today->modify($currentWeek->endOfWeek()->subDay()),
            ];
        })
            ->flatten()
            ->toArray();

        return array_merge($weeks, $subWeeks);
    }

    public function getWeeklyPeriodsProperty()
    {
        return collect($this->periods)
            ->chunk(2)
            ->map(fn(Collection $chunk) => $this->getDaysFrom($chunk));
    }

    public function getWeeklyMonthLabelsProperty()
    {
        return collect($this->weeklyPeriods)
            ->map(function (array $periods) {
                return collect($periods)
                    ->map(
                        fn(DateTimeImmutable $date) => $this->isSunday($date) ? null : $date->format($this->dateFormat)
                    )
                    ->filter();
            });
    }

    public function getWeeklyDayLabelsProperty()
    {
        return collect($this->weeklyPeriods)
            ->map(function (array $periods) {
                return collect($periods)
                    ->map(
                        fn(DateTimeImmutable $date) => $this->isSunday($date) ? null : $date->format('D dS')
                    )
                    ->filter();
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
            ->whereNotIn('role', ['Admin', 'Owner'])
            ->with([
                'dailyNumbers' => function ($query) {
                    $query
                        ->where('office_id', $this->selectedOffice)
                        ->whereBetween('date', [
                            today()->subWeeks(3)->startOfWeek()->format('Y-m-d'),
                            today()->endOfMonth()->format('Y-m-d'),
                        ])
                        ->orderBy('date', 'asc');
                },
            ])
            ->get()
            ->map(function (User $user) {
                $user->dailyNumbers = $user->dailyNumbers->groupBy(function (DailyNumber $dailyNumber) {
                    return (new Carbon($dailyNumber->date))->format($this->dateFormat);
                });

                return $user;
            });
    }

    public function getTotalsProperty()
    {
        $totals = [];

        foreach ($this->periodsLabel as $key => $period) {
            foreach ($this->weeklyMonthLabels[$key] as $label) {
                if ($this->users->isEmpty()) {
                    $totals[$label] = $this->getMappedDailyNumbers(collect(), $label);

                    continue;
                }

                $dailyNumbers = $this->getDailyNumbersGroupedByDate($this->users);

                $totals[$label] = $this->getMappedDailyNumbers($dailyNumbers, $label);
            }
        }

        return array_chunk($totals, 6, true);
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

    public function getOfficesProperty()
    {
        return match (user()->role) {
            'Admin', 'Owner'     => Office::oldest('name')->get(),
            'Department Manager' => $this->getOfficesFromDepartment(),
            'Region Manager'     => $this->getOfficesFromRegion(),
            'Office Manager'     => $this->getManagedOffices(),
            default              => collect()
        };
    }

    private function getOfficesFromDepartment()
    {
        /** @var Department $department */
        $department = Department::find(user()->department_id);

        if ($department === null) {
            return collect();
        }

        if ($department->offices->isNotEmpty()) {
            return $department->offices()->oldest('name')->get();
        }

        return collect();
    }

    private function getOfficesFromRegion()
    {
        return Office::query()
            ->oldest('name')
            ->whereIn('region_id', user()->managedRegions->pluck('id'))
            ->get();
    }

    private function getManagedOffices()
    {
        return Office::query()
            ->oldest('name')
            ->whereIn('id', user()->managedOffices->pluck('id'))
            ->get();
    }

    public function sumOf(string $field, $user, array $weekDays)
    {
        $formattedDates = collect($weekDays)->map(
            fn(DateTimeImmutable $date) => $date->format('Y-m-d')
        );

        if (collect($user->dailyNumbers)->isEmpty()) {
            return 0;
        }

        return collect($user->dailyNumbers)
            ->flatten()
            ->whereIn('date', $formattedDates)
            ->sum($field);
    }

    public function sumTotalOf(string $field, $totals, array $weekDays)
    {
        $formattedDates = collect($weekDays)->map(
            fn(DateTimeImmutable $date) => $date->format('Y-m-d')
        );

        return collect($totals)
            ->filter()
            ->map(function ($data) use ($formattedDates, $field) {
                if (in_array($data['date'], $formattedDates->toArray())) {
                    return $data[$field];
                }

                return 0;
            })
            ->flatten()
            ->sum();
    }

    private function getDailyNumbersGroupedByDate(Collection $users)
    {
        return $users
            ->map(fn(User $user) => $user->dailyNumbers)
            ->flatten()
            ->groupBy(
                fn(DailyNumber $dailyNumber) => (new Carbon($dailyNumber->date))->format($this->dateFormat)
            );
    }

    private function getMappedDailyNumbers(Collection $dailyNumbers, string $key)
    {
        $hasDailyNumbers = $dailyNumbers->isNotEmpty() && $dailyNumbers->has($key);

        return [
            'doors'         => $hasDailyNumbers ? $dailyNumbers[$key]->sum('doors') : 0,
            'sets'          => $hasDailyNumbers ? $dailyNumbers[$key]->sum('sets') : 0,
            'set_closes'    => $hasDailyNumbers ? $dailyNumbers[$key]->sum('set_closes') : 0,
            'closes'        => $hasDailyNumbers ? $dailyNumbers[$key]->sum('closes') : 0,
            'hours_worked'  => $hasDailyNumbers ? $dailyNumbers[$key]->sum('hours_worked') : 0,
            'hours_knocked' => $hasDailyNumbers ? $dailyNumbers[$key]->sum('hours_knocked') : 0,
            'sats'          => $hasDailyNumbers ? $dailyNumbers[$key]->sum('sats') : 0,
            'closer_sits'   => $hasDailyNumbers ? $dailyNumbers[$key]->sum('closer_sits') : 0,
            'date'          => $hasDailyNumbers ? $dailyNumbers[$key]->first()->date : null,
        ];
    }

    private function getDaysFrom(Collection $chunk)
    {
        $interval = new DateInterval('P1D');

        $firstPiece  = $chunk->first();
        $secondPiece = $chunk->last();

        $days = [$firstPiece];

        $weekDays = new DatePeriod($firstPiece, $interval, $secondPiece, DatePeriod::EXCLUDE_START_DATE);

        foreach ($weekDays as $day) {
            if ($this->isSunday($day)) {
                continue;
            }

            $days[] = $day;
        }

        return $days;
    }

    private function isSunday(DateTimeInterface $day)
    {
        return $day->format('w') === '0';
    }

    private function formattedDatesCollection(Collection $weeklyPeriods)
    {
        return $weeklyPeriods
            ->flatten()
            ->map(fn(DateTimeImmutable $date) => $date->format('Y-m-d'));
    }
}
