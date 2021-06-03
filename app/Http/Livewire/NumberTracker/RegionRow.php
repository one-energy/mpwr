<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class RegionRow extends Component
{
    public Region $region;

    public Collection $selectedOfficesId;

    public Collection $selectedUsersId;

    public bool $itsSelected = false;

    public bool $itsOpen = false;

    public int $quantityOfficesSelected = 0;

    protected $listeners = [
        'toggleOffice',
        'toggleUser',
    ];

    public function mount()
    {
        $this->selectedUsersId   = collect();
        $this->selectedOfficesId = collect();
    }

    public function render()
    {
        return view('livewire.number-tracker.region-row');
    }

    public function sumOf($property)
    {
        $sum = $this->region->offices->sum(function ($office) use ($property) {
            return $office->dailyNumbers->sum($property);
        });

        return $sum > 0 ? $sum : html_entity_decode('&#8212;');
    }

    public function collapseRegion()
    {
        $this->itsOpen = !$this->itsOpen;
    }

    public function selectRegion()
    {
        if ($this->itsSelected) {
            $this->selectedUsersId   = $this->selectedUsersId->merge($this->getUserIds());
            $this->selectedOfficesId = $this->selectedOfficesId->merge($this->region->offices->map->id);
        } else {
            $this->selectedUsersId   = collect([]);
            $this->selectedOfficesId = collect([]);
        }
        $this->emit('regionSelected', $this->region->id, $this->itsSelected);
    }

    public function getOfficesProperty()
    {
        return $this->region->offices;
    }

    public function anyOfficeSelected()
    {
        $this->itsSelected = $this->selectedOfficesId->isNotEmpty();
    }

    public function toggleOffice(Office $office, bool $insert)
    {
        if ($insert) {
            $this->selectedOfficesId->push($office->id);
            $this->selectedUsersId = $this->selectedUsersId->merge($office->dailyNumbers->unique('user_id')->map->user_id);
        } else {
            $this->selectedOfficesId = $this->selectedOfficesId->filter(fn($officeId) => $officeId !== $office->id);
            $this->selectedUsersId = $this->selectedUsersId->filter( function ($id) use($office) {
                return !$office->dailyNumbers->unique('user_id')->map(function ($dailyNumber) {
                        return $dailyNumber->user_id;
                    })->filter()->contains($id);
                }
                
            );
        }
        $this->anyOfficeSelected();
    }

    public function toggleUser($userId, bool $insert, $officeId)
    {
        if ($insert) {
            $this->selectedUsersId->push($userId);
            $this->selectedOfficesId = $this->selectedOfficesId->merge($officeId)->unique();
        } else {
            $this->selectedUsersId = $this->selectedUsersId->filter(fn($id) => $userId !== $id);
        }
    }

    private function getUserIds()
    {
        return $this->region->offices->map(function ($office) {
            return $office->dailyNumbers->unique('user_id')->map(function ($dailyNumber) {
                return $dailyNumber->user_id;
            })->filter();
        })->flatten();
    }
}
