<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Livewire\Component;

class ProfileStats extends Component
{
    public $user;

    public $userId;

    public $totalDoors;

    public $totalHours;

    public $totalSets;

    public $totalSits;

    public $totalCloses;

    public $dpsRatio;

    public $hpsRatio;

    public $sitRatio;

    public $closeRatio;

    protected $listeners = ['rerenderUser' => '$refresh'];

    public function mount($userId)
    {
        $this->user = User::find($userId);

        $query = $this->user->dailyNumbers;

        $this->totalDoors  = $query->sum('doors');
        $this->totalHours  = $query->sum('hours');
        $this->totalSets   = $query->sum('sets');
        $this->totalSits   = $query->sum('sits');
        $this->totalCloses = $query->sum('set_closes');

        $this->dpsRatio   = $this->totalDoors / $this->totalSets;
        $this->hpsRatio   = $this->totalHours / $this->totalSets;
        $this->sitRatio   = $this->totalSits / $this->totalSets;
        $this->closeRatio = $this->totalCloses / $this->totalSits;
    }

    public function render()
    {
        return view('livewire.profile-stats');
    }
}
