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
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

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

    public Collection $dailyNumbers;

    public array $newDailyNumbers;

    public function mount()
    {
        $this->selectedOffice  = $this->offices->first()->id;
        $this->newDailyNumbers = [];
        $this->dailyNumbers    = $this->users->pluck('dailyNumbers')->flatten();
    }

    public function render()
    {
        return view('livewire.number-tracker.spreadsheet');
    }

    public function updatedSelectedOffice()
    {
        $this->newDailyNumbers = [];
        $this->dailyNumbers    = $this->users->pluck('dailyNumbers')->flatten();
    }

    public function save()
    {
        if ($this->dailyNumbers->isNotEmpty()) {
            $this->update();
        }

        if (collect($this->newDailyNumbers)->isNotEmpty()) {
            $this->store();
        }

        alert()
            ->withTitle(__('Number Trackers saved!'))
            ->livewire($this)
            ->send();
    }

    private function update()
    {
        DB::beginTransaction();

        try {
            $this->dailyNumbers->each(function ($dailyNumber) {
                $dailyNumber['hours_worked'] = $this->calculateHoursWorked($dailyNumber);

                DailyNumber::query()
                    ->where('id', $dailyNumber['id'])
                    ->update($dailyNumber);

                DB::commit();
            });
        } catch (Throwable $e) {
            DB::rollBack();
        }
    }

    private function store()
    {
        DB::beginTransaction();

        try {
            collect($this->newDailyNumbers)->each(function ($dailyNumber) {
                $dailyNumber['hours_worked'] = $this->calculateHoursWorked($dailyNumber);

                DailyNumber::create($dailyNumber);
                DB::commit();
            });

            $this->newDailyNumbers = [];
        } catch (Throwable $e) {
            DB::rollBack();
        }
    }

    /**
     * One hours_knocked = 1h
     * One closes = 1h
     * One closer_sits = 2h
     */
    private function calculateHoursWorked($dailyNumber): float | int
    {
        return $dailyNumber['hours_knocked'] + $dailyNumber['closes'] + ($dailyNumber['closer_sits'] * 2);
    }

    public function updateDailyNumber(DailyNumber $dailyNumber, string $field, string $newValue)
    {
        $newValue = preg_replace('/[^0-9.]+/', '', $newValue);

        if ($newValue === '') {
            return;
        }

        $this->dailyNumbers = $this->dailyNumbers->map(function ($tracker) use ($dailyNumber, $field, $newValue) {
            if ($tracker['id'] === $dailyNumber->id) {
                $tracker[$field] = abs($newValue);
            }

            return $tracker;
        });
    }

    public function attachNewDailyEntry($index, User $user, $date, string $field, string $value)
    {
        $value = preg_replace('/[^0-9.]+/', '', $value);

        if ($value === '') {
            return;
        }

        if (!array_key_exists($index, $this->newDailyNumbers)) {
            $this->newDailyNumbers[$index] = [
                'user_id'       => $user->id,
                'office_id'     => $user->office_id,
                'date'          => (new Carbon($date))->format('Y-m-d'),
                'doors'         => 0,
                'sets'          => 0,
                'set_closes'    => 0,
                'closes'        => 0,
                'hours_worked'  => 0,
                'hours_knocked' => 0,
                'sats'          => 0,
                'closer_sits'   => 0,
            ];
        }

        $this->newDailyNumbers[$index] = array_merge(
            $this->newDailyNumbers[$index], [$field => abs($value)]
        );
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
            ->whereNotIn('role', ['Admin', 'Owner'])
            ->with([
                'dailyNumbers' => function ($query) {
                    $query
                        ->where('office_id', $this->selectedOffice)
                        ->whereBetween('date', [
                            today()->startOfMonth()->format('Y-m-d'),
                            today()->endOfMonth()->format('Y-m-d'),
                        ])
                        ->orderBy('date', 'asc');
                },
            ])
            ->get()
            ->map(function (User $user) {
                $user->dailyNumbers = $user->dailyNumbers->groupBy(function (DailyNumber $dailyNumber) {
                    return (new Carbon($dailyNumber->date))->format('F dS');
                });

                return $user;
            });
    }

    public function getTotalsProperty()
    {
        $totals = [];

        foreach ($this->periodsLabel as $key => $period) {
            foreach ($this->weeklyLabels[$key] as $label) {
                if ($this->users->isEmpty()) {
                    $totals[$label] = [];

                    continue;
                }

                $dailyNumbers = $this->getDailyNumbersGroupedByDate($this->users);

                $totals[$label] = isset($dailyNumbers[$label])
                    ? $this->getMappedDailyNumbers($dailyNumbers, $label)
                    : [];
            }
        }

        return array_chunk($totals, 7, true);
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
        return match (user()->role) {
            'Admin', 'Owner' => Office::oldest('name')->get(),
            'Region Manager' => Office::oldest('name')->whereIn('region_id', user()->managedRegions->pluck('id'))->get(),
            'Office Manager' => Office::oldest('name')->whereIn('id', user()->managedOffices->pluck('id'))->get(),
            default          => collect()
        };
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
        return [
            'doors'         => $dailyNumbers[$key]->sum('doors'),
            'sets'          => $dailyNumbers[$key]->sum('sets'),
            'set_closes'    => $dailyNumbers[$key]->sum('set_closes'),
            'closes'        => $dailyNumbers[$key]->sum('closes'),
            'hours_worked'  => $dailyNumbers[$key]->sum('hours_worked'),
            'hours_knocked' => $dailyNumbers[$key]->sum('hours_knocked'),
            'sats'          => $dailyNumbers[$key]->sum('sats'),
            'closer_sits'   => $dailyNumbers[$key]->sum('closer_sits'),
            'date'          => $dailyNumbers[$key]->first()->date,
        ];
    }
}
