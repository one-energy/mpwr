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

    public bool $selectedTotal = false;

    public $listeners = [
        'regionSelected',
        'toogleUser',
    ];

    public function mount()
    {
        $this->selectedUsers = collect();
    }

    public function render()
    {
        return view('livewire.number-tracker.office-row');
    }

    public function collapseOffice()
    {
        $this->itsOpen = !$this->itsOpen;
    }

    public function selectOffice()
    {
        if ($this->selected) {
            $this->selectedUsers = $this->selectedUsers->merge($this->office->dailyNumbers->unique('user_id')->pluck('user_id'));
        } else {
            $this->selectedUsers = $this->selectedUsers->empty();
        }

        $this->emitUp('toggleOffice', $this->office, $this->selected);
        $this->emit('officeSelected', $this->office->id, $this->selected);
        $this->isAnyUserSelected();
    }

    public function selectTotal()
    {
        $this->selected = $this->selectedTotal;
        $this->selectOffice();
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

    public function toogleUser(int $userId, bool $isSelected)
    {
        if ($isSelected) {
            $this->selectedUsers->push($userId);
        } else {
            $this->selectedUsers = $this->selectedUsers->filter(function ($selectedUser) use ($userId) {
                return $selectedUser != $userId;
            });
        }

        $this->isAnyUserSelected();
    }

    public function isAnyUserSelected()
    {  
        $this->selected = $this->selectedUsers->isNotEmpty();

        $this->emitUp('toggleOffice', $this->office, $this->selected);

        if (!$this->selected) {
            $this->emit('officeSelected', $this->office->id, $this->selected);
        }

        if ($this->selectedUsers->count() == $this->office->dailyNumbers->unique('user_id')->count()) {
            $this->selectedTotal = true;
        } else {
            
            $this->selectedTotal = false;
        }
    }
}
