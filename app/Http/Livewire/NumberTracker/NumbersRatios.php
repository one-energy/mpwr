<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use Illuminate\Support\Collection;
use Livewire\Component;

class NumbersRatios extends Component
{

    public array $offices = [];

    public array $users   = [];

    public $numbers;

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
        $this->numbers = DailyNumber::whereIn('office_id', $this->offices)
            ->whereIn('user_id', $this->users)
            ->withTrashed()
            ->get();
    }

    public function updateNumbers(array $offices = [], array $users = [])
    {
        $this->offices   = $offices;
        $this->users     = $users;
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
            return $this->sets > 0
                ? number_format($this->numbers->sum('hours_worked') / $this->numbers->sum('sets'), 2)
                : '-';
        }
    }

    public function getSitRatiosProperty()
    {
        if (isset($this->numbers)) {
            return $this->sets > 0
                ? number_format(($this->numbers->sum('sits') + $this->numbers->sum('set_sits')) / $this->sets, 2)
                : '-';
        }
    }

    public function getCloseRatioProperty()
    {
        $sitsPlusSetsits = $this->numbers->sum('sits') + $this->numbers->sum('set_sits');
        if (isset($this->numbers)) {
            return $sitsPlusSetsits > 0
                ? number_format(
                    ($this->numbers->sum('set_closes') + $this->numbers->sum('closes')) / $sitsPlusSetsits,
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
