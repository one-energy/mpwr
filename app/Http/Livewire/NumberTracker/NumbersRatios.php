<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use Illuminate\Support\Collection;
use Livewire\Component;

class NumbersRatios extends Component
{

    private array $regions = [];

    private array $offices = [];

    private array $users   = [];

    private Collection $numbers;

    protected $listeners = ['updateNumbers'];

    public function mount()
    {
        $this->getNumbers();
    }

    public function render()
    {
        return view('livewire.number-tracker.numbers-ratios');
    }

    public function getNumbers()
    {
        $this->numbers = DailyNumber::whereIn('office_id', $this->offices)->get();
    }

    public function updateNumbers(array $regions = [], array $offices = [], array $users = [])
    {
        $this->regions = $regions;
        $this->offices = $offices;
        $this->users = $users;
        $this->getNumbers();
    }

    public function getDpsProperty()
    {
        if (isset($this->numbers)) {
            return $this->sets > 0
                ? number_format($this->numbers->sum('doors') / $this->sets , 2)
                : '-';
        }
    }

    public function getHpsProperty()
    {
        if (isset($this->numbers)) {
            return $this->numbers->sum('sets') > 0
                ? number_format($this->numbers->sum('hours') / $this->numbers('sets'), 2)
                : '-';
        }
    }

    public function getSitRatioProperty()
    {
        if (isset($this->numbers)) {
            return $this->numbers['sets'] > 0
                ? number_format(($this->numbers['sits'] + $this->numbers['setSits']) / $this->sets, 2)
                : '-';
        }
    }

    public function getCloseProperty()
    {
        if (isset($this->totals)) {
            return $this->totals['sits'] + $this->totals['setSits'] > 0
                ? number_format(
                    ($this->totals['setCloses'] + $this->totals['closes']) / ($this->totals['sits'] + $this->totals['setSits']),
                    2
                )
                : '-';
        }
    }

    public function getSetsProperty()
    {
        return $this->numbers->sum('sets');
    }
}
