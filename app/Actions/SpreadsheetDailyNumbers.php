<?php

namespace App\Actions;

use App\Models\DailyNumber;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SpreadsheetDailyNumbers
{
    public function execute(array $dailyNumbers): void
    {
        $flattenedDailyNumbers = collect($dailyNumbers)->flatten(2);

        $recordsToUpdate = $flattenedDailyNumbers->filter(
            fn($dailyNumber) => array_key_exists('id', $dailyNumber)
        );

        if ($flattenedDailyNumbers->count() !== $recordsToUpdate->count()) {
            $this->massInsertDailyNumbers(
                $flattenedDailyNumbers->filter(fn($dailyNumber) => !array_key_exists('id', $dailyNumber))
            );
        }

        if ($recordsToUpdate->isNotEmpty()) {
            $this->updateDailyNumbers($recordsToUpdate);
        }
    }

    private function massInsertDailyNumbers(Collection $dailyNumbers)
    {
        if ($dailyNumbers->isEmpty()) {
            return;
        }

        DB::transaction(fn() => DailyNumber::insert($this->getMappedDailyNumbers($dailyNumbers)));
    }

    private function getMappedDailyNumbers(Collection $dailyNumbers)
    {
        return $dailyNumbers->map(function ($dailyNumber) {
            $dailyNumber['date']         = (new Carbon($dailyNumber['date']))->format('Y-m-d');
            $dailyNumber['hours_worked'] = $this->calculateHoursWorked($dailyNumber);
            $dailyNumber['created_at']   = now();
            $dailyNumber['updated_at']   = now();

            return $dailyNumber;
        })
            ->toArray();
    }

    private function updateDailyNumbers(Collection $dailyNumbers)
    {
        DB::transaction(function () use ($dailyNumbers) {
            $dailyNumbers->each(function ($dailyNumber) {
                $dailyNumber['hours_worked'] = $this->calculateHoursWorked($dailyNumber);

                DailyNumber::where('id', $dailyNumber['id'])->update($dailyNumber);
            });
        });
    }

    /**
     * One hours_knocked = 1h
     * One closes = 1h
     * One closer_sits = 2h
     */
    private function calculateHoursWorked($dailyNumber): float|int
    {
        return $dailyNumber['hours_knocked'] + $dailyNumber['closes'] + ($dailyNumber['closer_sits'] * 2);
    }
}
