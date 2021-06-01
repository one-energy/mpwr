<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * @property-read Collection|Department[] $departments
 */
class NumberTrackerDetailAccordionTable extends Component
{
    use FullTable;

    public bool $deleteds = false;

    public Collection $selectedUsers;

    public Collection $selectedOfficesIds;

    public string $period;

    public $selectedDate;

    public int $selectedDepartment;

    protected $listeners = [
        'setDateOrPeriod',
        'toogleRegion',
        'toogleOffice',
        'toogleUser',
    ];

    public function mount()
    {
        $this->selectedDepartment = $this->getDepartmentId();

        $this->sortBy = 'doors';
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
        return 'doors';
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
        $regions = Region::when($this->deleteds, function ($query) {   
                $query->has("offices.dailyNumbers")->withTrashed();
            })
            ->where('department_id', $this->selectedDepartment)
            ->with('offices.dailyNumbers')  
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

        $this->initRegionsData();
        $this->initUnselectedCollections();
    }

    private function getDepartmentId()
    {
        $departmentId = user()->department_id;

        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            $departmentId = $this->departments->first()?->id;
        }

        return $departmentId ?? 0;
    }

    public function toogleRegion(array $offices, $insert)
    {
        if ($insert) {
            $this->selectedOfficesIds->merge($offices);
        } else {
            $this->selectedOfficesIds->except($offices);
        }
    }

}
