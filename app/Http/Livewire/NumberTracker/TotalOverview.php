<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * @property-read Collection $dailyNumbers
 * @property-read Collection $lastDailyNumbers
 */
class TotalOverview extends Component
{
    public bool $withTrashed = false;

    public array $users = [];

    public array $offices = [];

    public string $period = 'd';

    public Carbon $date;

    protected $listeners = [
        'setDateOrPeriod',
        'updateNumbers'
    ];

    public function mount()
    {
        $this->date = today();
    }

    public function render()
    {
        return view('livewire.number-tracker.total-overview');
    }

    public function setDateOrPeriod($date, $period)
    {
        $this->period = $period;
        $this->date   = new Carbon($date);

        $this->users   = [];
        $this->offices = [];
    }

    public function updateNumbers($payload)
    {
        $this->users       = collect($payload['users'])->unique()->values()->toArray();
        $this->offices     = collect($payload['offices'])->unique()->values()->toArray();
        $this->withTrashed = $payload['withTrashed'];
    }

    public function getDailyNumbersProperty()
    {
        return DailyNumber::query()
            ->when($this->withTrashed, fn($query) => $query->withTrashed())
            ->whereIn('user_id', $this->users)
            ->whereIn('office_id', $this->offices)
            ->inPeriod($this->period, $this->date)
            ->get();
    }

    public function getLastDailyNumbersProperty()
    {
        return DailyNumber::query()
            ->when($this->withTrashed, fn($query) => $query->withTrashed())
            ->whereIn('office_id', $this->offices)
            ->inPeriod($this->period, $this->date)
            ->get();
    }

    public function getOverviewFieldsProperty()
    {
        return ['hours worked', 'doors', 'hours knocked', 'sets', 'sats', 'set closes', 'closer sits'];
    }

    public function sumDailyNumbersBy(string $field)
    {
        return $this->dailyNumbers->sum($this->formatString($field));
    }

    public function differenceFromLastDailyNumbersBy(string $field)
    {
        $formattedField = $this->formatString($field);

        return $this->dailyNumbers->sum($formattedField) - $this->lastDailyNumbers->sum($formattedField);
    }

    private function formatString(string $value)
    {
        return Str::slug(strtolower($value), '_');
    }
}
