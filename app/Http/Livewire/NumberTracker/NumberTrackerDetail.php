<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use App\Models\Department;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * @property-read array $pills
 */
class NumberTrackerDetail extends Component
{
    use FullTable;

    public array $selectedUsersIds = [];

    public array $selectedOfficesIds = [];

    public string $period = 'd';

    public bool $deleteds = false;

    public Collection $topTenTrackers;

    public $date;

    public $dateSelected;

    public string $selectedPill = 'hours worked';

    public int $selectedDepartment;

    protected $listeners = [
        'sumTotalNumbers',
        'loadingSumNumberTracker',
        'updateNumbers',
        'onSelectedDepartment' => 'changeSelectedDepartment',
    ];

    public function mount()
    {
        $this->selectedDepartment = $this->getDepartmentId();

        $this->dateSelected = date('Y-m-d', time());
    }

    public function render()
    {
        $this->topTenTrackers = $this->getTopTenTrackers();

        return view('livewire.number-tracker.number-tracker-detail');
    }

    public function sortBy()
    {
        return 'doors';
    }

    public function setPeriod($p)
    {
        $this->period = $p;
        $this->emit('setDateOrPeriod', $this->dateSelected, $this->period);
    }

    public function setDate()
    {
        $this->dateSelected = date('Y-m-d', strtotime($this->date));
        $this->emit('setDateOrPeriod', $this->dateSelected, $this->period);
    }

    public function changeSelectedDepartment(int $departmentId)
    {
        $this->selectedDepartment = $departmentId;
        $this->topTenTrackers     = $this->getTopTenTrackers();
    }

    public function getPillsProperty()
    {
        return ['hours worked', 'doors', 'hours knocked', 'sets', 'sats', 'set closes', 'closer sits', 'closes'];
    }

    public function updateNumbers($payload)
    {
        $this->selectedUsersIds = $payload["users"];
        $this->selectedOfficesIds = $payload["offices"];
    }

    private function getTopTenTrackers()
    {
        if (!in_array($this->selectedPill, $this->pills, true)) {
            return collect();
        }

        return DailyNumber::query()
        ->withTrashed()
        ->with('user', function($query) {
            $query->withTrashed();
        })
        ->whereIn('user_id', $this->selectedUsersIds)
        ->whereIn('office_id', $this->selectedOfficesIds)
        ->inPeriod($this->period, new Carbon($this->date))
        ->orderBy('total', 'desc')
        ->groupBy('user_id')
        ->select(
            DB::raw($this->getTotalRawQuery($this->getSluggedPill())),
            'user_id'
        )
        ->limit(10)
        ->get();
    }

    private function getSluggedPill()
    {
        return strtolower(Str::slug($this->selectedPill, '_'));
    }

    private function getTotalRawQuery(string $pill)
    {
        $rawQuery = sprintf('SUM(%s) as total', $pill);

        return $rawQuery;
    }

    private function getDepartmentId()
    {
        return user()->hasAnyRole(['Admin', 'Owner'])
            ? Department::oldest('name')->first()->id
            : (user()->department_id ?? 0);
    }
}
