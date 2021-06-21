<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use Illuminate\Support\Carbon;
use Livewire\Component;

class NumbersRatios extends Component
{
    public array $offices = [];

    public array $users   = [];

    public string $period = 'd';

    public $numbers;

    public Carbon $date;

    protected $listeners = [
        'setDateOrPeriod',
        'updateNumbers',
    ];

    public function mount()
    {
        $this->date = today();
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
            ->inPeriod($this->period, $this->date)
            ->withTrashed()
            ->get();
    }

    public function updateNumbers($payload)
    {
        $this->offices   = collect($payload['offices'])->unique()->values()->toArray();
        $this->users     = collect($payload['users'])->unique()->values()->toArray();
        $this->getNumbers();
    }

    public function setDateOrPeriod($date, $period)
    {
        $this->period = $period;
        $this->date   = new Carbon($date);
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
        if (isset($this->numbers)) {
            $closesSum = $this->numbers->sum('closes');

            return $closesSum > 0
                ? number_format($this->numbers->sum('closer_sits') / $closesSum, 2)
                : '-';
        }
    }

    public function getSetsProperty()
    {
        return $this->numbers->sum('sets');
    }
}
