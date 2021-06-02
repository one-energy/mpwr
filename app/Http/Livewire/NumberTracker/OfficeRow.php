<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class OfficeRow extends Component
{
    public Office $office;

    public Collection $selectedUsers;

    public bool $itsOpen = false;

    public bool $selected = false;

    public $listeners = [
        'regionSelected',
        'toogleUser',
    ];

    public function render()
    {
        $this->selectedUsers = collect();
        return view('livewire.number-tracker.office-row');
    }

    public function collapseOffice()
    {
        $this->itsOpen = !$this->itsOpen;
    }

    public function selectOffice()
    {
        $this->emitUp('toggleOffice', $this->office, $this->selected);
        $this->emit('officeSelected', $this->office->id, $this->selected);
    }

    public function regionSelected(int $regionId, bool $selected)
    {
        if ($this->office->region_id === $regionId) {
            $this->selected = $selected;
            $this->emit('officeSelected', $this->office->id, $selected);
        }
    }

    public function sumBy($field)
    {
        return $this->parseNumber(
            $this->office->dailyNumbers->sum(fn ($dailyNumber) => $dailyNumber[$field])
        );
    }

    public function parseNumber($value)
    {
        return $value > 0 ? $value : html_entity_decode('&#8212;');
    }

    public function getUsersDailyNumbersProperty()
    {
        return $this->office->dailyNumbers->groupBy("user_id");
    }

    public function toogleUser(User $user, bool $isSelected)
    {
        if ($isSelected) {
            $this->selectedUsers->push($user->id);
        } else {
            $this->selectedUsers = $this->selectedUsers->filter(function ($selectedUser) use ($user) {
                return $selectedUser != $user->id;
            });
        }

        $this->isAnyUserSelected();
    }

    public function isAnyUserSelected()
    {
        $this->selected = $this->selectedUsers->isNotEmpty();
        $this->emitUp('toggleOffice', $this->office, $this->selected);
    }
}
