<?php

namespace App\Services;

use App\Models\DailyNumber;
use App\Models\User;
use Illuminate\Support\Carbon;

final class NumberTrackingService
{
    public function updateOrCreateNumberTracking(array $validatedFields)
    {
        $user = User::findOrFail(array_keys($validatedFields['numbers'])[0]);

        /** @var Carbon */
        $date = $validatedFields['date'];

        $filteredNumbers = collect($validatedFields['numbers'][$user->id])
            ->filter(fn ($element) => (int)$element >= 0 || $element !== null);

        if ($filteredNumbers->isNotEmpty()) {
            DailyNumber::updateOrCreate([
                'user_id'   => $user->id,
                'date'      => $date->toDateString(),
                'office_id' => $user->office_id,
            ], $filteredNumbers->toArray());

            return;
        }

        $dailyNumber = DailyNumber::whereDate('date', $validatedFields['date'])
            ->whereUserId($user->id)
            ->first();

        if ($dailyNumber !== null) {
            DailyNumber::destroy($dailyNumber->id);
        }
    }
}
