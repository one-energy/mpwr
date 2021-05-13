<?php

namespace App\Actions;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UpdateOrCreateNumberTracking
{
    public function execute(array $data)
    {
        $data = $this->validate($data);
        /** @var Carbon */
        $date = $data['date'];

        collect($data['numbers'])->each(function ($n, $key) use ($data, $date) {
            $user = User::find($key);

            if ($user === null) {
                return;
            }

            $filteredNumbers = collect($data['numbers'][$key])
                ->filter(fn($element) => (int)$element >= 0 || $element !== null);

            if ($filteredNumbers->isNotEmpty()) {
                $filteredNumbers['hours_worked'] = $this->calculateHoursWorked($filteredNumbers);
                $user->dailyNumbers()->updateOrCreate([
                    'user_id'   => $user->id,
                    'date'      => $date->toDateString(),
                    'office_id' => $user->office_id,
                ], $filteredNumbers->toArray());

                return;
            }

            $dailyNumber = $user->dailyNumbers()
                ->whereDate('date', $data['date'])
                ->first();

            if ($dailyNumber !== null) {
                $dailyNumber->delete();
            }
        });
    }

    private function validate(array $data)
    {
        $data['date'] = $data['date'] ? new Carbon($data['date']) : now();

        return Validator::make($data, [
            'officeSelected'          => 'required|integer',
            'date'                    => 'nullable|date',
            'numbers'                 => 'required|array',
            'numbers.*.hours_worked'  => 'required|integer|min:0',
            'numbers.*.doors'         => 'required|integer|min:0',
            'numbers.*.hours_knocked' => 'required|integer|min:0',
            'numbers.*.sets'          => 'required|integer|min:0',
            'numbers.*.sats'          => 'required|integer|min:0',
            'numbers.*.set_closes'    => 'required|integer|min:0',
            'numbers.*.closer_sits'   => 'required|integer|min:0',
            'numbers.*.closes'        => 'required|integer|min:0',
        ])->validate();
    }

    private function calculateHoursWorked($dailyNumber): float|int
    {
        return $dailyNumber['hours_knocked'] + $dailyNumber['closes'] + ($dailyNumber['closer_sits'] * 2);
    }
}
