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

        $user = User::find(array_keys($data['numbers'])[0]);

        if ($user === null) {
            return;
        }

        /** @var Carbon */
        $date = $data['date'];

        $filteredNumbers = collect($data['numbers'][$user->id])
            ->filter(fn ($element) => (int)$element >= 0 || $element !== null);

        if ($filteredNumbers->isNotEmpty()) {
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
    }

    private function validate(array $data)
    {
        $data['date'] = $data['date'] ? new Carbon($data['date']) : now();

        return Validator::make($data, [
            'officeSelected'       => 'required|integer',
            'date'                 => 'nullable|date',
            'numbers'              => 'required|array',
            'numbers.*.doors'      => 'required|integer|min:0|gte:numbers.*.sets',
            'numbers.*.hours'      => 'required|numeric|between:0,24',
            'numbers.*.sets'       => 'required|integer|min:0|gte:numbers.*.closes',
            'numbers.*.set_sits'   => 'required|integer|min:0',
            'numbers.*.sits'       => 'required|integer|min:0',
            'numbers.*.set_closes' => 'required|integer|min:0',
            'numbers.*.closes'     => 'required|integer|min:0',
        ])->validate();
    }
}
