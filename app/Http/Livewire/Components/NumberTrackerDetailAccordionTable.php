<?php

namespace App\Http\Livewire\Components;

use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class NumberTrackerDetailAccordionTable extends Component
{
    use FullTable;

    public array $itsOpenRegions;

    public array $lastPeriodNumberTracker;

    public $totals;

    public string $period;

    public $selectedDate;

    protected $listeners = ['setDateOrPeriod'];

    public function mount()
    {
        $this->sortBy = 'doors';
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

    public function sortBy()
    {
        return 'doors';
    }

    public function addItsOpen()
    {
        unset($this->itsOpenRegions);
        $this->itsOpenRegions = array();
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

        $this->lastPeriodNumberTracker = $this->last_regions->map(function ($region) {
            $region->itsOpen  = false;
            $region->selected = true;
            $region->sortedOffices = $region->offices->map(function ($office) {
                $office->itsOpen = false;
                $office->selected = true;
                $office->sortedDailyNumbers = $office->dailyNumbers->map(function ($dailyNumbers) {
                    $dailyNumbers->selected = true;
                    return $dailyNumbers;
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
                ->with('user');
        }])->whereHas('offices.dailyNumbers', function ($query) {
            $query->inPeriod($this->period, new Carbon($this->selectedDate));
        })->has('offices.dailyNumbers');

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

            $regions = $regions->map(function($region) {
                $region->offices = $region->offices->sortBy(function ($office) {
                    return $office->dailyNumbers->count() ? $office->dailyNumbers->sum($this->sortBy) : 0;
                })->values();
                return $region;
            });

            $regions = $regions->map(function($region) {
                $region->offices = $region->offices->map(function ($office) {
                    $office->dailyNumbers = $office->dailyNumbers->sortBy($this->sortBy)->values();
                    return $office;
                });
                return $region;
            });
        } else {
            $regions = $regions->sortByDesc(function($region) {
                return $region->offices->sum(function ($office) {
                    return $office->dailyNumbers->count() ? $office->dailyNumbers->sum($this->sortBy) : 0;
                });
            })->values();

            $regions = $regions->map(function($region) {
                $region->offices = $region->offices->sortByDesc(function ($office) {
                    return $office->dailyNumbers->count() ? $office->dailyNumbers->sum($this->sortBy) : 0;
                })->values();
                return $region;
            });

            $regions = $regions->map(function($region) {
                $region->offices = $region->offices->map(function ($office) {
                    $office->dailyNumbers = $office->dailyNumbers->sortByDesc($this->sortBy)->values();
                    return $office;
                });
                return $region;
            });
        }


        return $regions;
    }

    public function getLastRegionsProperty()
    {
        $query = Region::with(['offices.dailyNumbers' => function ($dailynumbersQuery) {
            $dailynumbersQuery->inLastPeriod($this->period, new Carbon($this->selectedDate))
                ->with(['user' => function($query) {
                    $query->withTrashed();
                }]);
        }])->whereHas('offices.dailyNumbers', function ($query) {
            $query->inLastPeriod($this->period, new Carbon($this->selectedDate));
        });
        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            return $query->get();
        }
        return $query->whereDepartmentId(user()->department_id ?? 0)->get();
    }

    public function collapseRegion( int $regionIndex)
    {
        $this->itsOpenRegions[$regionIndex]['itsOpen'] = !$this->itsOpenRegions[$regionIndex]['itsOpen'];
    }

    public function collapseOffice( int $regionIndex, int $officeIndex)
    {
        $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['itsOpen'] = !$this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['itsOpen'];
    }

    public function selectRegion($regionIndex)
    {
        $this->itsOpenRegions[$regionIndex]['selected'] = !$this->itsOpenRegions[$regionIndex]['selected'];
        foreach ($this->itsOpenRegions[$regionIndex]['sortedOffices'] as &$office) {
            $office['selected'] = $this->itsOpenRegions[$regionIndex]['selected'];
            foreach ($office['sortedDailyNumbers'] as &$dailyNumber) {
                $dailyNumber['selected'] = $this->itsOpenRegions[$regionIndex]['selected'];
            }
        }
        $this->sumTotal();
    }

    public function selectOffice($regionIndex, $officeIndex)
    {

        foreach ($this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedDailyNumbers'] as &$dailyNumber) {

            $dailyNumber['selected'] = $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected'];
        }
        $this->sumTotal();
    }

    public function selectUser($regionIndex, $officeIndex, $userIndex)
    {
        $this->sumTotal();
    }

    public function sumTotal(){
        $regions     = collect($this->itsOpenRegions);
        $regionsLast = collect($this->lastPeriodNumberTracker);
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
            return count($office->sortedDailyNumbers) ? $office->sortedDailyNumbers->sum($field) : 0;
        }
    }

    public function getCollectionOf($array)
    {
        return collect($array)->map(fn ($element) => $element);
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
    }
}
