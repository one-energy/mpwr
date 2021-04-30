<?php

namespace App\Http\Livewire\Components;

use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class NumberTrackerDetailAccordionTable extends Component
{
    use FullTable;

    public array $itsOpenRegions;

    public Collection $unselectedRegions;

    public Collection $unselectedOffices;

    public Collection $unselectedDailyNumbers;

    public $totals;

    public string $period;

    public $selectedDate;

    protected $listeners = ['setDateOrPeriod'];

    public function mount()
    {
        $this->sortBy = 'doors';
        $this->initUnselectedCollections();
        $this->initRegionsData();
    }

    public function render()
    {
        return view('livewire.components.number-tracker-detail-accordion-table');
    }

    public function initRegionsData()
    {
        $this->addItsOpen();
        $this->sumTotal();
    }

    public function initUnselectedCollections()
    {
        $this->unselectedRegions      = collect([]);
        $this->unselectedOffices      = collect([]);
        $this->unselectedDailyNumbers = collect([]);
    }

    public function sortBy()
    {
        return 'doors';
    }

    public function addItsOpen()
    {
        $this->itsOpenRegions = $this->regions->map(function ($region) {
            $region->itsOpen  = false;
            $region->selected = true;
            $region->sortedOffices = $region->offices->map(function ($office) {
                $office->itsOpen = false;
                $office->selected = true;
                $office->sortedDailyNumbers = $office->dailyNumbers->map(function ($user) {
                    $user->selected = true;
                    return $user;
                })->toArray();
                return $office;
            })->toArray();

            return $region;
        })->toArray();
    }

    public function getRegionsProperty()
    {
        $query = Region::with(['offices.dailyNumbers' => function ($dailynumbersQuery) {
            $dailynumbersQuery->inPeriod($this->period, new Carbon($this->selectedDate))
                ->with(['user' => function($query) {
                    $query->withTrashed();
                }])
                ->groupBy('user_id')
                ->selectRaw('*')
                ->selectRaw('SUM(doors) as doors')
                ->selectRaw('SUM(hours) as hours')
                ->selectRaw('SUM(sets) as sets')
                ->selectRaw('SUM(set_sits) as set_sits')
                ->selectRaw('SUM(sits) as sits')
                ->selectRaw('SUM(set_closes) as set_closes')
                ->selectRaw('SUM(closes) as closes');

        }])->whereHas('offices.dailyNumbers', function ($query) {
            $query->inPeriod($this->period, new Carbon($this->selectedDate))
            ->with(['user' => function($query) {
                $query->withTrashed();
            }]);
        });

        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            $regions = $query->get();
        } else {
            $regions = $query->whereDepartmentId(user()->department_id ?? 0)->get();
        }

        if ($this->sortDirection == 'asc') {
            $regions = $regions->sortBy(function($region) {
                return $region->offices->sum(function ($office) {
                    return $office->dailyNumbers->count() ? $office->dailyNumbers->sum($this->sortBy) : 0;
                });
            })->values();
        } else {
            $regions = $regions->sortByDesc(function($region) {
                return $region->offices->sum(function ($office) {
                    return $office->dailyNumbers->count() ? $office->dailyNumbers->sum($this->sortBy) : 0;
                });
            })->values();
        }

        return $regions;
    }

    public function getLastRegionsProperty()
    {
        $query = Region::with(['offices.dailyNumbers' => function ($dailynumbersQuery) {
            $dailynumbersQuery->inLastPeriod($this->period, new Carbon($this->selectedDate));
        }]);


        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            return $query->get();
        }
        return $query->whereDepartmentId(user()->department_id ?? 0)->get();
    }

    public function getLastDailyNumbers()
    {
       return $this->lastRegions->map(function ($region) {
            $region->selected = !$this->unselectedRegions->contains($region->id);
            $region->sortedOffices = $region->offices->map(function ($office) {
                $office->selected = !$this->unselectedOffices->contains($office->id);;
                $office->sortedDailyNumbers = $office->dailyNumbers->map(function ($dailyNumbers) {
                    $dailyNumbers->selected = !$this->unselectedDailyNumbers->contains($dailyNumbers->id);;
                    return $dailyNumbers;
                })->toArray();
                return $office;
            })->toArray();
            return $region;
        })->toArray();
    }

    public function collapseRegion(int $regionIndex)
    {
        $collectionOffices = collect($this->itsOpenRegions[$regionIndex]['sortedOffices']);
        if ($this->sortDirection == 'asc') {
            $this->itsOpenRegions[$regionIndex]['sortedOffices'] = $collectionOffices->sortBy(function ($office) {
                $collectionDailyNumbers = collect($office['daily_numbers']);
                return $office['sortedDailyNumbers'] ? $collectionDailyNumbers->sum($this->sortBy) : 0;
            })->values();
        } else {
            $this->itsOpenRegions[$regionIndex]['sortedOffices'] = $collectionOffices->sortByDesc(function ($office) {
                $collectionDailyNumbers = collect($office['daily_numbers']);
                return $office['sortedDailyNumbers'] ? $collectionDailyNumbers->sum($this->sortBy) : 0;
            })->values();
        }
        $this->itsOpenRegions[$regionIndex]['itsOpen'] = !$this->itsOpenRegions[$regionIndex]['itsOpen'];
    }

    public function collapseOffice(int $regionIndex, int $officeIndex)
    {
        $collectionDailyNumbers = collect($this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers']);
        if ($this->sortDirection == 'asc') {
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'] = $collectionDailyNumbers->sortBy($this->sortBy)->values();
        } else {
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'] = $collectionDailyNumbers->sortByDesc($this->sortBy)->values();
        }
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
                    $this->unselectedDailyNumbers,
                    $dailyNumber['id'],
                    !$dailyNumber['selected']
                );
            }
        }
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
                $this->unselectedDailyNumbers,
                $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['id'],
                !$dailyNumber['selected']
            );
        }

        if(!$this->itsOpenRegions[$regionIndex]['selected'] && $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected']) {
            $this->itsOpenRegions[$regionIndex]['selected'] = true;
        } else {
            $this->itsOpenRegions[$regionIndex]['selected'] = array_search(true, array_column($this->itsOpenRegions[$regionIndex]['sortedOffices'], 'selected')) !== false;
        }

        $this->addOrRemoveOf(
            $this->unselectedRegions,
            $this->itsOpenRegions[$regionIndex]['id'],
            !$this->itsOpenRegions[$regionIndex]['selected']
        );

        $this->sumTotal();
    }

    public function selectDailyNumberUser($regionIndex, $officeIndex, $dailyNumberIndex)
    {
        $this->addOrRemoveOf(
            $this->unselectedDailyNumbers,
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'][$dailyNumberIndex]['id'],
            !$this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'][$dailyNumberIndex]['selected']
        );

        if(!$this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected'] && $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'][$dailyNumberIndex]['selected']) {
            $this->itsOpenRegions[$regionIndex]['selected'] = true;
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected'] = true;
        } else {
            $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected'] = array_search(true, array_column($this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'], 'selected')) !== false;
            $this->itsOpenRegions[$regionIndex]['selected'] = array_search(true, array_column($this->itsOpenRegions[$regionIndex]['sortedOffices'], 'selected')) !== false;
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

        $this->sumTotal();
    }

    public function sumTotal(){
        $this->emitUp('updateLeaderBoard', $this->unselectedRegions, $this->unselectedOffices, $this->unselectedDailyNumbers);
        $regions     = collect($this->itsOpenRegions);
        $regionsLast = collect($this->getLastDailyNumbers());
        $this->totals = [
            'doors'         => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'doors', true)),
            'hours'         => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'hours', true)),
            'sets'          => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sets', true)),
            'setSits'       => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_sits', true)),
            'sits'          => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sits', true)),
            'setCloses'     => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_closes', true)),
            'closes'        => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'closes', true)),
            'doorsLast'     => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'doors', true)),
            'hoursLast'     => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'hours', true)),
            'setsLast'      => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sets', true)),
            'setSitsLast'   => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_sits', true)),
            'sitsLast'      => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sits', true)),
            'setClosesLast' => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_closes', true)),
            'closesLast'    => $regionsLast->sum(fn($region) => $this->sumRegionNumberTracker($region, 'closes', true)),
        ];
    }

    public function sumRegionNumberTracker($region, $field, $filterBySelected = false)
    {
        $sum = 0;
        $region = (object) $region;
        if ($region->selected || !$filterBySelected) {
            $collectOffice = $this->getCollectionOf($region->sortedOffices);
            $sum = $collectOffice->sum(function ($office) use ($filterBySelected, $field) {
                return $this->sumOfficeNumberTracker($office, $field, $filterBySelected);
            });
        }
        return $sum;
    }

    public function sumOfficeNumberTracker($office, $field, $filterBySelected = false)
    {
        $office = (object) $office;
        if ($office->selected || !$filterBySelected) {
            $office->sortedDailyNumbers = $this->getCollectionOf($office->sortedDailyNumbers);
            return $office->sortedDailyNumbers->sum(function ($dailyNumber) use ($field, $filterBySelected) {
                return $dailyNumber['selected'] || !$filterBySelected ? $dailyNumber[$field] : 0;
            });
        }
    }

    public function getCollectionOf($array)
    {
        return collect($array)->map(fn ($element) => $element);
    }

    public function addOrRemoveOf(collection &$array , int $id, bool $conditionToAdd)
    {
        if($conditionToAdd) {
            $array->push($id);
        } else {
            $array = $array->filter(function ($item) use ($id) {
                return $item != $id;
            });
        }
    }

    public function getDps()
    {
        if(isset($this->totals)) {
            return $this->totals['sets'] > 0 ? number_format($this->totals['doors'] / $this->totals['sets'], 2) : '-';
        }
    }

    public function getHps()
    {
        if(isset($this->totals)) {
            return $this->totals['sets'] > 0 ? number_format($this->totals['hours'] / $this->totals['sets'], 2) : '-';
        }
    }

    public function getSitRatio()
    {
        if(isset($this->totals)) {
            return $this->totals['sets'] > 0 ? number_format(($this->totals['sits'] + $this->totals['setSits']) / $this->totals['sets'], 2) : '-';
        }
    }

    public function getCloseRatio()
    {
        if(isset($this->totals)) {
            return $this->totals['sits'] + $this->totals['setSits'] > 0 ?
                number_format(($this->totals['setCloses'] + $this->totals['closes']) /
                                ($this->totals['sits'] + $this->totals['setSits']), 2) : '-';
        }
    }

    public function getNumberTrackerSumOf($property)
    {
        if(isset($this->totals)) {
            return  $this->totals[$property] ?? 0;
        }
    }

    public function getNumberTrackerDifferenceToLasNumbersOf($property)
    {
        $propertyLast = $property . 'Last';
        if(isset($this->totals)) {
            return  $this->totals[$property] - $this->totals[$propertyLast];
        }
    }

    public function setDateOrPeriod($date, $period)
    {
        $this->selectedDate = $date;
        $this->period       = $period;
        $this->initRegionsData();
        $this->initUnselectedCollections();
    }
}
