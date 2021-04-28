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

    protected $listeners = ['setDateOrPeriod' , 'sortTable' => 'initRegionsData'];

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

    public function addItsOpen($keepOpenAndSelect = null)
    {
        unset($this->itsOpenRegions);
        $this->itsOpenRegions = array();
        $this->itsOpenRegions = $this->regions->map(function ($region) {
            $region->itsOpen  = false;
            $region->selected = true;
            $region->sortedOffices = $region->offices->map(function ($office) {
                $office->itsOpen = false;
                $office->selected = true;
                $office->sortedUsers = $office->users->map(function ($user) {
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
                $office->sortedUsers = $office->users->map(function ($user) {
                    $user->selected = true;
                    return $user;
                })->toArray();
                return $office;
            })->toArray();
            return $region;
        })->toArray();
        // dd( $this->itsOpenRegions);
    }

    public function getRegionsProperty()
    {
        $query = Region::with(['offices.dailyNumbers' => function ($dailynumbersQuery) {
            $dailynumbersQuery->inPeriod($this->period, new Carbon($this->selectedDate))
                ->with('user')
                ->groupBy('user_id');
        }])->whereHas('offices.users.dailyNumbers', function ($query) {
            $query->inPeriod($this->period, new Carbon($this->selectedDate));
        });

        dd($query->get()->toArray());
        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            $regions = $query->get();
        } else {
            $regions = $query->whereDepartmentId(user()->department_id ?? 0)->get();
        }

        if ($this->sortDirection == 'asc') {
            $regions = $regions->sortBy(function($region) {
                return $region->offices->sum(function ($office) {
                    return $office->users->sum(function ($user) {
                        return $user->dailyNumbers->sum($this->sortBy);
                    });
                });
            })->values();

            $regions = $regions->map(function($region) {
                $region->offices = $region->offices->sortBy(function ($office) {
                    return $office->users->sum(function ($user) {
                        return $user->dailyNumbers->sum($this->sortBy);
                    });
                })->values();
                return $region;
            });

            $regions = $regions->map(function($region) {
                $region->offices = $region->offices->map(function ($office) {
                    $office->users = $office->users->sortBy(function ($user) {
                        return $user->dailyNumbers->sum($this->sortBy);
                    })->values();
                    return $office;
                });
                return $region;
            });
        } else {
            $regions = $regions->sortByDesc(function($region) {
                return $region->offices->sum(function ($office) {
                    return $office->users->sum(function ($user) {
                        return $user->dailyNumbers->sum($this->sortBy);
                    });
                });
            })->values();

            $regions = $regions->map(function($region) {
                $region->offices = $region->offices->sortByDesc(function ($office) {
                    return $office->users->sum(function ($user) {
                        return $user->dailyNumbers->sum($this->sortBy);
                    });
                })->values();
                return $region;
            });

            $regions = $regions->map(function($region) {
                $region->offices = $region->offices->map(function ($office) {
                    $office->users = $office->users->sortByDesc(function ($user) {
                        return $user->dailyNumbers->sum($this->sortBy);
                    })->values();
                    return $office;
                });
                return $region;
            });
        }


        return $regions;
    }

    public function getLastRegionsProperty()
    {
        $query = Region::with(['offices.users.dailyNumbers' => function ($dailynumbersQuery) {
            $dailynumbersQuery->inLastPeriod($this->period, new Carbon($this->selectedDate));
        }])->whereHas('offices.users.dailyNumbers', function ($query) {
            $query->inLastPeriod($this->period, new Carbon($this->selectedDate));
        })->with(['offices.users' => function ($query) {
            $query->withTrashed();
        }]);

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
        foreach ($this->itsOpenRegions[$regionIndex]['sortedOffices'] as $office) {
            $office['selected'] = !$office['selected'];
            foreach ($office['sortedUsers'] as $users) {
                $users['selected'] = !$users['selected'];
            }
        }
        $this->sumTotal();
    }

    public function selectOffice($regionIndex, $officeIndex)
    {

        foreach ($this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['sortedUsers'] as &$user) {

            $user['selected'] = $this->itsOpenRegions[$regionIndex]['sortedOffices'][$officeIndex]['selected'];
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
            $office->sortedUsers = $this->getCollectionOf($office->sortedUsers);
            return $office->sortedUsers->sum(function ($user) use ($filterBySelected, $field,) {
               return $this->sumUserNumberTracker($user, $field, $filterBySelected);
            });
        }
    }

    public function sumUserNumberTracker($user, $field, $filterBySelected = false)
    {
        $user = (object) $user;
        $user->daily_numbers = collect($user->daily_numbers);
        if ($user->selected || !$filterBySelected) {
            return $user->daily_numbers->sum($field,);
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
