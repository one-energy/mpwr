<?php

namespace App\Http\Livewire\Components;

use App\Models\DailyNumber;
use App\Models\Region;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class NumberTrackerDetailAccordionTable extends Component
{
    use FullTable;

    public array $itsOpenRegions;

    public $totals;

    public string $period;

    public $selectedDate;

    protected $listeners = ['setDateOrPeriod'];

    public function mount()
    {
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
            $region->offices = $region->offices->map(function ($office) {
                $office->itsOpen = false;
                $office->selected = true;
                $office->users = $office->users->map(function ($user) {
                    $user->selected = true;
                    return $user;
                });
                return $office;
            });

            return $region;
        })->toArray();
    }

    public function getRegionsProperty()
    {
        $query = Region::with(['offices.users.dailyNumbers' => function ($dailynumbersQuery) {
            $dailynumbersQuery->inPeriod($this->period, new Carbon($this->selectedDate));
        }])->whereHas('offices.users.dailyNumbers', function ($query) {
            $query->inPeriod($this->period, new Carbon($this->selectedDate));
        })->with(['offices.users' => function ($query) {
            $query->withTrashed();
        }]);

        if (user()->hasAnyRole(['Admin', 'Owner'])) {
            $regions = $query->get();
        } else {
            $regions = $query->whereDepartmentId(user()->department_id ?? 0)->get();
        }

        $regions = $regions->sortBy(function($region) {
            return $region->offices->sum(function ($office) {
                return $office->users->sum(function ($user) {
                    return $user->dailyNumbers->sum('doors');
                });
            });
        })->values();

        $regions = $regions->map(function($region) {
            $region->offices = $region->offices->sortBy(function ($office) {
                return $office->users->sum(function ($user) {
                    return $user->dailyNumbers->sum('doors');
                });
            })->values();
            return $region;
        });



        $regions = $regions->map(function($region) {
            $region->offices = $region->offices->map(function ($office) {
                $office->users = $office->users->sortBy(function ($user) {
                    return $user->dailyNumbers->sum('doors');
                })->values();
                return $office;
            });
            return $region;
        });


        return $regions;
    }

    public function collapseRegion( int $regionIndex)
    {
        $this->itsOpenRegions[$regionIndex]['itsOpen'] = !$this->itsOpenRegions[$regionIndex]['itsOpen'];
    }

    public function collapseOffice( int $regionIndex, int $officeIndex)
    {
        $this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['itsOpen'] = !$this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['itsOpen'];
    }

    public function selectRegion($regionIndex)
    {
        $this->itsOpenRegions[$regionIndex]['selected'] = !$this->itsOpenRegions[$regionIndex]['selected'];
        $this->sumTotal();
    }

    public function selectOffice($regionIndex, $officeIndex)
    {
        $this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['selected'] = !$this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['selected'];
        $this->sumTotal();
    }

    public function selectUser($regionIndex, $officeIndex, $userIndex)
    {
        $this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['users'][$userIndex]['selected'] = !$this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['users'][$userIndex]['selected'];
        $this->selected = $this->itsOpenRegions[$regionIndex]['offices'][$officeIndex]['users'][$userIndex]['selected'];
        $this->sumTotal();
    }

    public function sumTotal(){
        $this->dispatchBrowserEvent('loading-number-tracker');
        $regions = collect($this->itsOpenRegions);
        $this->totals = [
            'doors'         => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'doors', true)),
            'hours'         => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'hours', true)),
            'sets'          => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sets', true)),
            'setSits'       => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_sits', true)),
            'sits'          => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sits', true)),
            'setCloses'     => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_closes', true)),
            'closes'        => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'closes', true)),
            'doorsLast'     => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'doors', true)),
            'hoursLast'     => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'hours', true)),
            'setsLast'      => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sets', true)),
            'setSitsLast'   => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_sits', true)),
            'sitsLast'      => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'sits', true)),
            'setClosesLast' => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'set_closes', true)),
            'closesLast'    => $regions->sum(fn($region) => $this->sumRegionNumberTracker($region, 'closes', true)),
        ];
        $this->dispatchBrowserEvent('endloading-number-tracker');
        $this->emit('sumTotalNumbers', $this->totals);
    }

    public function sumRegionNumberTracker($region, $field, $filterBySelected = false)
    {
        $sum = 0;
        $region = (object) $region;
        if ($region->selected || !$filterBySelected) {
            $collectOffice = $this->getCollectionOf($region->offices);
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
            $office->users = $this->getCollectionOf($office->users);
            return $office->users->sum(function ($user) use ($filterBySelected, $field,) {
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
