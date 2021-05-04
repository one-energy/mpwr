<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * @property-read Collection|Office[] $offices
 */
class Spreadsheet extends Component
{
    public int $selectedOffice;

    public function mount()
    {
        $this->selectedOffice = $this->offices->first()->id;
    }

    public function render()
    {
        return view('livewire.number-tracker.spreadsheet');
    }

    public function getIndicatorsProperty()
    {
        return [
            ['label' => 'HW', 'description' => 'Hours Worked'],
            ['label' => 'D', 'description' => 'Doors'],
            ['label' => 'HK', 'description' => 'Hours Knocked'],
            ['label' => 'S', 'description' => 'Sets'],
            ['label' => 'SA', 'description' => 'Sats'],
            ['label' => 'SC', 'description' => 'Set Closes'],
            ['label' => 'CS', 'description' => 'Closer Sits'],
            ['label' => 'C', 'description' => 'Closes'],
        ];
    }

    public function getIsAdminProperty()
    {
        return user()->hasAnyRole(['Admin', 'Owner']);
    }

    public function getOfficesProperty()
    {
        return Office::get();
    }
}
