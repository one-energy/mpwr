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

    public function getDpsProperty()
    {
        if (isset($this->numbers)) {
            return $this->numbers->sum('sets') > 0
                ? number_format($this->numbers->sum('doors') / $this->numbers->sum('sets'), 2)
                : '-';
        }
    }

    public function updateNumbers(array $regions = [], array $offices = [], array $users = [])
    {
        $this->regions = $regions;
        $this->offices = $offices;
        $this->users = $users;
        $this->getNumbers();
    }

    public function getHps()
    {
        // if (isset($this->totals)) {
        //     return $this->totals['sets'] > 0
        //         ? number_format($this->totals['hours'] / $this->totals['sets'], 2)
        //         : '-';
        // }
    }

    public function getSitRatio()
    {
        // if (isset($this->totals)) {
        //     return $this->totals['sets'] > 0
        //         ? number_format(($this->totals['sits'] + $this->totals['setSits']) / $this->totals['sets'], 2)
        //         : '-';
        // }
    }

    public function getCloseRatio()
    {
        // if (isset($this->totals)) {
        //     return $this->totals['sits'] + $this->totals['setSits'] > 0
        //         ? number_format(
        //             ($this->totals['setCloses'] + $this->totals['closes']) / ($this->totals['sits'] + $this->totals['setSits']),
        //             2
        //         )
        //         : '-';
        // }
    }
}
