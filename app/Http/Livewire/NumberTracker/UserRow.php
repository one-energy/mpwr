<?php

namespace App\Http\Livewire\NumberTracker;

use Livewire\Component;

class UserRow extends Component
{
    public bool $isSelected;

    public function render()
    {
        return view('livewire.number-tracker.user-row');
    }
}
