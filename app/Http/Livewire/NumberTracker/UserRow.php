<?php

namespace App\Http\Livewire\NumberTracker;

use Illuminate\Support\Collection;
use Livewire\Component;

class UserRow extends Component
{
    public Collection $usersDailyNumbers;

    public bool $isSelected;

    public function render()
    {
        return view('livewire.number-tracker.user-row');
    }
    
}
