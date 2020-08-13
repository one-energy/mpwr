<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Livewire\Component;

class ProfileStats extends Component
{
    public $user = '';

    public $totalDoors = '';

    public $totalHours = '';

    public $totalSets = '';

    public $totalSits = '';

    public $totalCloses = '';

    public function mount(int $userId)
    {
        $this->setUser($userId);
    }

    public function render()
    {
        return view('livewire.profile-stats');
    }

    public function setUser(int $userId)
    {
        $this->user = User::find($userId);

        $query = User::query()
            ->leftJoin('daily_numbers', function($join) {
                $join->on('daily_numbers.user_id', '=', 'users.id');
            })
            ->where('users.id', $userId);

        $this->totalDoors = $query
            ->select(DB::raw('sum(daily_numbers.doors) as doors'))
            ->whereNotNull('daily_numbers.doors')
            ->pluck('doors')->toArray();

        $this->totalHours = $query
            ->select(DB::raw('sum(daily_numbers.hours) as hours'))
            ->whereNotNull('daily_numbers.hours')
            ->pluck('hours')->toArray();

        $this->totalSets = $query
            ->select(DB::raw('sum(daily_numbers.sets) as sets'))
            ->whereNotNull('daily_numbers.sets')
            ->pluck('sets')->toArray();

        $this->totalSits = $query
            ->select(DB::raw('sum(daily_numbers.sits) as sits'))
            ->whereNotNull('daily_numbers.sits')
            ->pluck('sits')->toArray();

        $this->totalCloses = $query
            ->select(DB::raw('sum(daily_numbers.set_closes) as set_closes'))
            ->whereNotNull('daily_numbers.set_closes')
            ->pluck('set_closes')->toArray();
    }
}
