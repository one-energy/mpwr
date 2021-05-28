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

    public array $itsOpenRegions;

    public Collection $unselectedRegions;

    public Collection $unselectedOffices;

    public Collection $unselectedUserDailyNumbers;

    public Collection $openedRegions;

    public Collection $openedOffices;

    public bool $deleteds = false;

    public $totals;

    public string $period;

    public $selectedDate;

    public int $selectedDepartment;

    protected $listeners = ['setDateOrPeriod'];

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

    public function selectRegion($regionIndex)
    {
        $this->addOrRemoveOf(
            $this->unselectedRegions,
            $this->itsOpenRegions[$regionIndex]['id'],
            !$this->itsOpenRegions[$regionIndex]['selected']
        );
        foreach ($this->itsOpenRegions[$regionIndex]['sortedOffices'] as &$office) {
            $office['selected'] = $this->itsOpenRegions[$regionIndex]['selected'];
            $this->addOrRemoveOf(
                $this->unselectedOffices,
                $office['id'],
                !$office['selected']
            );
            foreach ($office['sortedDailyNumbers'] as &$dailyNumber) {
                $dailyNumber['selected'] = $this->itsOpenRegions[$regionIndex]['selected'];
                $this->addOrRemoveOf(
                    $this->unselectedUserDailyNumbers,
                    $dailyNumber['user_id'],
                    !$dailyNumber['selected']
                );
            }
        }

        $result = $this->extractOfficeAndUser($this->itsOpenRegions);

        $this->emit('updateNumbers', [
            'users'       => $result->pluck('user_id'),
            'offices'     => $result->pluck('office_id'),
            'withTrashed' => $this->deleteds,
        ]);

        $this->sumTotal();
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

}
