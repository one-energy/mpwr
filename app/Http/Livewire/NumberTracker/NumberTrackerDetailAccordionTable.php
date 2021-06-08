<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Department;
use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

/**
 * @property-read Collection|Department[] $departments
 */
class NumberTrackerDetailAccordionTable extends Component
{
    use FullTable;

    public bool $deleteds = false;

    public string $period;

    public $selectedDate;

    public int $selectedDepartment;

    protected $listeners = [
        'setDateOrPeriod',
        'updateIds',
    ];

    public function mount()
    {
        $this->selectedDepartment = $this->getDepartmentId();

        $this->sortBy = 'hours_worked';
    }

    public function render()
    {
        return view('livewire.number-tracker.number-tracker-detail-accordion-table');
    }

    public function updatedSelectedDepartment()
    {
        $this->emitUp('onSelectedDepartment', $this->selectedDepartment);
    }

    public function sortBy()
    {
        return 'hours_worked';
    }

    public function getDepartmentsProperty()
    {
        return match (user()->role) {
            'Admin', 'Owner' => Department::oldest('name')->get(),
            default => []
        };
    }

    public function getRegionsProperty()
    {
        $regions = Region::query()
            ->where('department_id', $this->selectedDepartment)
            ->with([
                'offices' => function ($query) {
                    $query->when($this->deleteds, function ($query) {
                        $query->withTrashed()
                            ->where(function ($query) {
                                $query->whereHas('dailyNumbers', function ($query) {
                                    $query->inPeriod($this->period,
                                        new Carbon($this->selectedDate))->withTrashed();
                                })
                                    ->whereNotNull('deleted_at');
                            })
                            ->orWhereNull('deleted_at');
                    })
                        ->with([
                            'dailyNumbers' => function ($query) {
                                $query
                                    ->when($this->deleteds, fn($query) => $query->withTrashed())
                                    ->when(!$this->deleteds, fn($query) => $query->has('user'))
                                    ->inPeriod($this->period, new Carbon($this->selectedDate));
                            },
                        ]);
                },
            ])
            ->get();

        if ($this->sortDirection === 'asc') {
            return $regions->sortBy(function ($region) {
                return $region->offices->sum(function ($office) {
                    return $office->dailyNumbers->count() ? $office->dailyNumbers->sum($this->sortBy) : 0;
                });
            })->values();
        }

        return $regions->sortByDesc(function ($region) {
            return $region->offices->sum(function ($office) {
                return $office->dailyNumbers->count() ? $office->dailyNumbers->sum($this->sortBy) : 0;
            });
        })->values();
    }

    public function setDateOrPeriod($date, $period)
    {
        $this->selectedDate = $date;
        $this->period       = $period;
    }

    private function getDepartmentId()
    {
        $departmentId = user()->department_id;

        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            $departmentId = $this->departments->first()?->id;
        }

        return $departmentId ?? 0;
    }

    public function updateIds()
    {
        $officeKey  =  sprintf('%s-region-offices-ids', user()->id);
        $userKey    =  sprintf('%s-region-users-ids', user()->id);

        $offices = collect(json_decode(Cache::get($officeKey)));
        $users   = collect(json_decode(Cache::get($userKey)));

        $this->emitUpdateNumbersEvent($offices, $users);
    }

    public function emitUpdateNumbersEvent(Collection $offices, Collection $users)
    {
        $this->emit('updateNumbers', [
            'users'       => $users->flatten()->toArray(),
            'offices'     => $offices->flatten()->toArray(),
            'withTrashed' => $this->deleteds,
        ]);
    }

    public function cleanRegionCache()
    {
        $officeKey  =  sprintf('%s-region-offices-ids', user()->id);
        $userKey    =  sprintf('%s-region-users-ids', user()->id);

        Cache::forget($officeKey);
        Cache::forget($userKey);
    }
}
