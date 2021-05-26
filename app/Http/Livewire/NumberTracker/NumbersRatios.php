<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use Illuminate\Support\Collection;
use Livewire\Component;

class NumbersRatios extends Component
{

    private array $offices = [];

    private array $users   = [];

    private bool $deleteds = false;

    private $numbers;

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
            ->when($this->deleteds, function($query) {
                $query->withTrashed();
            })
            ->get();
    }

    public function updateNumbers(array $offices = [], array $users = [], bool $deleteds = false)
    {
        $this->offices   = $offices;
        $this->users     = $users;
        $this->$deleteds = $deleteds;
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
                ? number_format($this->numbers->sum('hours') / $this->numbers->sum('sets'), 2)
                : '-';
        }
    }

    public function getSitRatiosProperty()
    {
        if (isset($this->numbers)) {
            return $this->sets > 0
                ? number_format(($this->numbers->sum('sits') + $this->numbers->sum('setSits')) / $this->sets, 2)
                : '-';
        }
    }

    public function getCloseRatioProperty()
    {
        $sitsPlusSetsits = $this->numbers->sum('sits') + $this->numbers->sum('setSits');
        if (isset($this->numbers)) {
            return $sitsPlusSetsits > 0
                ? number_format(
                    ($this->numbers->sum('setCloses') + $this->numbers->sum('closes')) / $sitsPlusSetsits,
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
