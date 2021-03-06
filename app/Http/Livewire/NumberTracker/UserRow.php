<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class UserRow extends Component
{
    public Collection | array $userDailyNumbers;

    public User $user;

    public bool $isSelected = false;

    protected $listeners = [
        'officeSelected',
    ];

    public function mount()
    {
        $this->userDailyNumbers = collect($this->userDailyNumbers);
        $this->user             = User::withTrashed()->find($this->userDailyNumbers[0]['user_id']);
    }

    public function render()
    {
        return view('livewire.number-tracker.user-row');
    }

    public function selectUser()
    {
        $this->emitUp('toggleUser', $this->user->id, $this->isSelected, $this->userDailyNumbers[0]['office_id']);
    }

    public function officeSelected(int $officeId, bool $selected)
    {
        if ($this->userDailyNumbers[0]['office_id'] === $officeId) {
            $this->isSelected = $selected;
        }
    }
}
