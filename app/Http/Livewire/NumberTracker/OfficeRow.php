<?php

namespace App\Http\Livewire\NumberTracker;

use Livewire\Component;

class OfficeRow extends Component
{
    public array $office;

    public int $officeIndex;

    public function render()
    {
        return view('livewire.number-tracker.office-row');
    }

    public function collapseOffice()
    {
        $this->office['itsOpen'] = !$this->office['itsOpen'];
    }

    public function selectOffice()
    {
        $this->emit('officeSelected', $this->officeIndex);
    }

    public function sumBy($field)
    {
        $sum = collect($this->office['sortedDailyNumbers'])
            ->sum(fn ($dailyNumber) => $dailyNumber[$field]);

        return $this->parseNumber($sum);
    }

    public function parseNumber($value)
    {
        return $value > 0 ? $value : html_entity_decode('&#8212;');
    }
}
