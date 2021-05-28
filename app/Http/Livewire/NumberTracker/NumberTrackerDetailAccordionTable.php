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
        $this->initUnselectedCollections();
        $this->initOpenedCollections();
        $this->initRegionsData();
    }

    public function render()
    {
        return view('livewire.number-tracker.number-tracker-detail-accordion-table');
    }

    public function updatedSelectedDepartment()
    {
        $this->initRegionsData();
        $this->emitUp('onSelectedDepartment', $this->selectedDepartment);
    }

    public function initRegionsData()
    {
        $this->addItsOpen();
        $this->sumTotal();
    }

    public function initUnselectedCollections()
    {
        $this->unselectedRegions          = collect([]);
        $this->unselectedOffices          = collect([]);
        $this->unselectedUserDailyNumbers = collect([]);
    }

    public function initOpenedCollections()
    {
        $this->openedRegions          = collect([]);
        $this->openedOffices          = collect([]);
        $this->openedUserDailyNumbers = collect([]);
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

    public function addItsOpen()
    {
        $this->itsOpenRegions = $this->regions->map(function ($region) {
            $region->itsOpen       = $this->openedRegions->contains($region->id);
            $region->selected      = $this->unselectedRegions->contains($region->id);

            return $region;
        })->toArray();
    }

    public function getRegionsProperty()
    {
        $regions = Region::when($this->deleteds, function ($query) {   
                $query->has("offices.dailyNumbers")->withTrashed();
            })
            ->where('department_id', $this->selectedDepartment)
            ->with('offices', function($query) {
                $query->withSum('dailyNumbers', 'hours_worked');
                $query->withSum('dailyNumbers', 'hours_knocked');
                $query->withSum('dailyNumbers', 'doors');
                $query->withSum('dailyNumbers', 'sets');
                $query->withSum('dailyNumbers', 'set_sits');
                $query->withSum('dailyNumbers', 'sats');
                $query->withSum('dailyNumbers', 'set_closes');
                $query->withSum('dailyNumbers', 'set_closes');
                $query->withSum('dailyNumbers', 'closes');
            })
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

    public function getLastRegionsProperty()
    {
        $query = Region::with([
            'offices.dailyNumbers' => function ($query) {
                $query->inLastPeriod($this->period, new Carbon($this->selectedDate))
                    ->when($this->deleteds, function ($query) {
                        $query->withTrashed();
                    });
            },
        ]);

        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            return $query->get();
        }

        return $query->whereDepartmentId(user()->department_id ?? 0)->get();
    }

    public function getLastDailyNumbers()
    {
        return $this->lastRegions->map(function ($region) {
            $region->selected      = $this->unselectedRegions->contains($region->id);
            $region->sortedOffices = $region->offices->map(function ($office) {
                $office->selected           = $this->unselectedOffices->contains($office->id);
                $office->sortedDailyNumbers = $office->dailyNumbers->map(function ($dailyNumbers) {
                    $dailyNumbers->selected = $this->unselectedUserDailyNumbers->contains($dailyNumbers->id);

                    return $dailyNumbers;
                })->toArray();

                return $office;
            })->toArray();

            return $region;
        })->toArray();
    }

    public function collapseRegion()
    {
        dd("teste");
        
    }

    public function collapseOffice(int $regionIndex, int $officeIndex)
    {
        $collectionDailyNumbers = collect($this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers']);
        if ($this->sortDirection === 'asc') {
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'] = $collectionDailyNumbers->sortBy($this->sortBy)->values();
        } else {
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'] = $collectionDailyNumbers->sortByDesc($this->sortBy)->values();
        }
        $this->addOrRemoveOf(
            $this->openedRegions,
            $this->itsOpenRegions[$regionIndex]['id'],
            !$this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['itsOpen']
        );
        $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['itsOpen'] = !$this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['itsOpen'];
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

    public function selectOffice($regionIndex, $officeIndex)
    {
        $this->addOrRemoveOf(
            $this->unselectedOffices,
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['id'],
            !$this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected']
        );
        foreach ($this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'] as &$dailyNumber) {
            $dailyNumber['selected'] = $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected'];
            $this->addOrRemoveOf(
                $this->unselectedUserDailyNumbers,
                $dailyNumber['user_id'],
                !$dailyNumber['selected']
            );
        }

        if (
            !$this->itsOpenRegions[$regionIndex]['selected'] &&
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected']
        ) {
            $this->itsOpenRegions[$regionIndex]['selected'] = true;
        } else {
            $this->itsOpenRegions[$regionIndex]['selected'] = array_search(
                true,
                array_column($this->itsOpenRegions[$regionIndex]['sortedOffices'], 'selected')
            ) !== false;
        }

        $this->addOrRemoveOf(
            $this->unselectedRegions,
            $this->itsOpenRegions[$regionIndex]['id'],
            !$this->itsOpenRegions[$regionIndex]['selected']
        );

        $result = $this->extractOfficeAndUser($this->itsOpenRegions);

        $this->emit('updateNumbers', [
            'users'       => $result->pluck('user_id'),
            'offices'     => $result->pluck('office_id'),
            'withTrashed' => $this->deleteds,
        ]);

        $this->sumTotal();
    }

    public function selectDailyNumberUser($regionIndex, $officeIndex, $dailyNumberIndex)
    {
        $this->addOrRemoveOf(
            $this->unselectedUserDailyNumbers,
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'][$dailyNumberIndex]['user_id'],
            !$this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'][$dailyNumberIndex]['selected']
        );

        if (
            !$this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected'] &&
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'][$dailyNumberIndex]['selected']
        ) {
            $this->itsOpenRegions[$regionIndex]['selected'] = true;

            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected'] = true;
        } else {
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected'] = array_search(
                true,
                array_column($this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'],
                    'selected')
            ) !== false;

            $this->itsOpenRegions[$regionIndex]['selected'] = array_search(
                true,
                array_column($this->itsOpenRegions[$regionIndex]['sortedOffices'], 'selected')
            ) !== false;
        }

        $this->addOrRemoveOf(
            $this->unselectedOffices,
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['id'],
            !$this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected']
        );

        $this->addOrRemoveOf(
            $this->unselectedRegions,
            $this->itsOpenRegions[$regionIndex]['id'],
            !$this->itsOpenRegions[$regionIndex]['selected']
        );

        $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['totalSelected'] = $this->allDailyNumbersAreSelected($regionIndex, $officeIndex);

        $result = $this->extractOfficeAndUser($this->itsOpenRegions);

        $this->emit('updateNumbers', [
            'users'       => $result->pluck('user_id'),
            'offices'     => $result->pluck('office_id'),
            'withTrashed' => $this->deleteds,
        ]);

        $this->sumTotal();
    }

    public function sumTotal()
    {
        $this->emitUp(
            'updateLeaderBoard',
            $this->unselectedRegions,
            $this->unselectedOffices,
            $this->unselectedUserDailyNumbers,
            $this->deleteds
        );

        $regions      = collect($this->itsOpenRegions);
        $regionsLast  = collect($this->getLastDailyNumbers());
        $this->totals = [
            'doors'        => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'doors', true)),
            'hours'        => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'hours', true)),
            'sets'         => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sets', true)),
            'setSits'      => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_sits', true)),
            'sits'         => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sits', true)),
            'setCloses'    => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_closes', true)),
            'closes'       => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'closes', true)),
            'hoursWorked'  => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'hours_worked', true)),
            'hoursKnocked' => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'hours_knocked', true)),
            'sats'         => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sats', true)),
            'closerSits'   => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'closer_sits', true)),

            'doorsLast'        => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'doors', true)),
            'hoursLast'        => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'hours', true)),
            'setsLast'         => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sets', true)),
            'setSitsLast'      => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_sits', true)),
            'sitsLast'         => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sits', true)),
            'setClosesLast'    => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_closes', true)),
            'closesLast'       => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'closes', true)),
            'hoursWorkedLast'  => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'hours_worked', true)),
            'hoursKnockedLast' => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'hours_knocked', true)),
            'satsLast'         => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sats', true)),
            'closerSitsLast'   => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'closer_sits', true)),
        ];
    }

    public function sumRegionNumberTracker($region, $field, $filterBySelected = false)
    {
        $sum    = 0;
        $region = (object)$region;
        if ($region->selected || !$filterBySelected) {
            $collectOffice = $this->getCollectionOf($region->sortedOffices);
            $sum           = $collectOffice->sum(function ($office) use ($filterBySelected, $field) {
                return $this->sumOfficeNumberTracker($office, $field, $filterBySelected);
            });
        }

        return $sum;
    }

    public function sumOfficeNumberTracker($office, $field, $filterBySelected = false)
    {
        $office = (object)$office;
        if ($office->selected || !$filterBySelected) {
            $office->sortedDailyNumbers = $this->getCollectionOf($office->sortedDailyNumbers);

            return $office->sortedDailyNumbers->sum(function ($dailyNumber) use ($field, $filterBySelected) {
                return $dailyNumber['selected'] || !$filterBySelected ? $dailyNumber[$field] : 0;
            });
        }
    }

    public function getCollectionOf($array)
    {
        return collect($array)->map(fn($element) => $element);
    }

    public function addOrRemoveOf(collection &$array, int $id, bool $conditionToAdd)
    {
        if ($conditionToAdd) {
            $array->push($id);
        } else {
            $array = $array->filter(function ($item) use ($id) {
                return $item != $id;
            });
        }
    }


    public function getNumberTrackerSumOf($property)
    {
        if (isset($this->totals)) {
            return $this->totals[$property] ?? 0;
        }
    }

    public function getNumberTrackerDifferenceToLasNumbersOf($property)
    {
        $propertyLast = $property . 'Last';
        if (isset($this->totals)) {
            return $this->totals[$property] - $this->totals[$propertyLast];
        }
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

    public function parseNumber($number)
    {
        return $number > 0 ? $number : html_entity_decode('&#8212;');
    }

    public function sumBy(Collection | array $dailyNumbers, string $field)
    {
        return collect($dailyNumbers)->sum($field);
    }

    public function toggleOffices($regionIndex, $officeIndex)
    {
        $office = $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex];

        $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected'] = $office['totalSelected'];

        $this->selectOffice($regionIndex, $officeIndex);
    }

    private function allDailyNumbersAreSelected($regionIndex, $officeIndex)
    {
        return collect(
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers']
        )->every(fn($dailyNumber) => $dailyNumber['selected']);
    }

    private function extractOfficeAndUser(array $itsOpenRegions)
    {
        return collect($itsOpenRegions)
            ->filter(fn($region) => $region['selected'])
            ->map(function ($region) {
                return collect($region['sortedOffices'])
                    ->filter(fn($office) => $office['selected'])
                    ->map(function ($office) {
                        return collect($office['sortedDailyNumbers'])
                            ->filter(fn($dailyNumber) => $dailyNumber['selected'])
                            ->map(function ($dailyNumber) {
                                return [
                                    'user_id'   => $dailyNumber['user_id'],
                                    'office_id' => $dailyNumber['office_id'],
                                ];
                            });
                    })
                    ->filter();
            })
            ->filter()
            ->flatten(2);
    }
}
