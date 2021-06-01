<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use Livewire\Component;

class OfficeRow extends Component
{
    public Office $office;

    public bool $itsOpen = false;

    public bool $selected = false;

    public $listeners = [
        'regionSelected',
    ];

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
        $this->emitUp('toggleOffice', $this->office, $this->selected);
    }

    public function regionSelected(int $regionId, bool $selected)
    {
        if ($this->office->region_id === $regionId) {
            $this->selected = $selected;
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
}
