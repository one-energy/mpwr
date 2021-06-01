<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use Livewire\Component;

class OfficeRow extends Component
{
    public Office $office;

    public bool $itsOpen = false;

    public bool $selected = false;

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
        $this->selected = $this->selected;

        $this->emitUp('toggleOffice', $this->office, $this->selected);
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
}
