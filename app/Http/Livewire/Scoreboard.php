<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Scoreboard extends Component
{
    public $filterTypes;

    public $userId;

    public $user;

    public $userArray = [];

    public $dpsRatio;

    public $hpsRatio;

    public $sitRatio;

    public $closeRatio;

    public Carbon $date;

    public string $period = 'd';

    public function mount()
    {
        $this->filterTypes = [
            ['index' => 'leaderboards', 'value' => 'Leaderboards'],
            ['index' => 'records', 'value' => 'Records'],
        ];

        $this->date = now();
    }

    public function setDate(string $date)
    {
        $this->date = new Carbon($date);
    }

    public function setPeriod(string $period)
    {
        if (!in_array($period, ['d', 'w', 'm'])) {
            return;
        }

        $this->period = $period;
    }

    public function render()
    {
        return view('livewire.scoreboard');
    }

    public function setUser($userId)
    {
        $this->userId = $userId;
        $this->user   = User::find($userId);

        $dailyNumbers = $this->user->dailyNumbers;

        $this->userArray = [
            'photo_url'   => $this->user->photo_url,
            'full_name'   => $this->user->full_name,
            'office_name' => $this->user->office->name,
            'totalDoors'  => $dailyNumbers->sum('doors'),
            'totalHours'  => $dailyNumbers->sum('hours'),
            'totalSets'   => $dailyNumbers->sum('sets'),
            'totalSits'   => $dailyNumbers->sum('sits'),
            'totalCloses' => $dailyNumbers->sum('set_closes'),
        ];

        if ($dailyNumbers->sum('sets') > 0) {
            $this->dpsRatio = ($dailyNumbers->sum('doors') / $dailyNumbers->sum('sets'));
            $this->hpsRatio = ($dailyNumbers->sum('hours') / $dailyNumbers->sum('sets'));
            $this->sitRatio = ($dailyNumbers->sum('sits') / $dailyNumbers->sum('sets'));
        } else {
            $this->dpsRatio = 0;
            $this->hpsRatio = 0;
            $this->sitRatio = 0;
        }

        if ($dailyNumbers->sum('sits') > 0) {
            $this->closeRatio = ($dailyNumbers->sum('set_closes') / $dailyNumbers->sum('sits'));
        } else {
            $this->closeRatio = 0;
        }

        $this->dispatchBrowserEvent('setUserNumbers', [
            'doors'      => $dailyNumbers->sum('doors'),
            'hours'      => $dailyNumbers->sum('hours'),
            'sets'       => $dailyNumbers->sum('sets'),
            'sits'       => $dailyNumbers->sum('sits'),
            'set_closes' => $dailyNumbers->sum('set_closes'),
        ]);
    }

    public function getTopTenDoorsProperty()
    {
        return $this->getTopTenUsersBy('doors');
    }

    public function getTopTenHoursProperty()
    {
        return $this->getTopTenUsersBy('hours');
    }

    public function getTopTenSetsProperty()
    {
        return $this->getTopTenUsersBy('sets');
    }

    public function getTopTenSetClosesProperty()
    {
        return $this->getTopTenUsersBy('set_closes');
    }

    public function getTopTenClosesProperty()
    {
        return $this->getTopTenUsersBy('closes');
    }

    public function getTopTenUsersBy(string $field): Collection
    {
        return User::query()
            ->with('office')
            ->select('id', 'first_name', 'last_name', 'department_id', 'office_id')
            ->withCount([
                "dailyNumbers as {$field}_total" => function ($query) use ($field) {
                    $query->select(DB::raw("SUM({$field}) as {$field}_total"))
                        ->groupBy('user_id')
                        ->inPeriod($this->period, $this->date);
                }
            ])
            ->where('department_id', user()->department_id)
            ->latest("{$field}_total")
            ->limit(10)
            ->get()
            ->filter(fn(User $user) => $user->{"{$field}_total"} > 0);
    }
}
