<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class UserRow extends Component
{
    public Collection $userDailyNumbers;

    public User $user;

    public bool $isSelected = false;

    protected $listeners = [
        'officeSelected'
    ];

    public function mount()
    {
        $this->user = $this->userDailyNumbers->first()->user()->withTrashed()->first();
    }

    public function render()
    {
        return view('livewire.number-tracker.user-row');
    }

    public function selectUser()
    {
        $this->emitUp("toogleUser", $this->user->id, $this->isSelected);
    }
    
    public function officeSelected(int $officeId, bool $selected)
    {
        if ($this->userDailyNumbers[0]->office_id === $officeId) {
            $this->isSelected = $selected;
        }
    }
}
