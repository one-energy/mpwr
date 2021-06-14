<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class RegionRow extends Component
{
    public int $regionId;

    public ?Region $region;

    public string $period;

    public string $selectedDate;

    public bool $withTrashed;

    public Collection $offices;

    public Collection $selectedOfficesId;

    public Collection $selectedUsersId;

    public bool $itsSelected = false;

    public bool $itsOpen = false;

    protected $listeners = [
        'toggleOffice',
        'toggleUser',
        'setDateOrPeriod',
        'sorted' => 'sortOffices',
    ];

    public function mount()
    {
        $this->region = null;

        $this->sortOffices('hours_worked', 'asc');

        $this->selectedUsersId   = collect();
        $this->selectedOfficesId = collect();
    }

    public function render()
    {
        $this->region = $this->findRegion($this->regionId);

        return view('livewire.number-tracker.region-row');
    }

    public function sumOf($property)
    {
        $sum = $this->region->offices->sum(function ($office) use ($property) {
            return $office->dailyNumbers->sum($property);
        });

        return $sum > 0 ? $sum : html_entity_decode('&#8212;');
    }

    public function collapseRegion()
    {
        $this->itsOpen = !$this->itsOpen;
    }

    public function selectRegion()
    {
        if ($this->itsSelected) {
            $this->selectedUsersId   = $this->selectedUsersId->merge($this->getUserIds());
            $this->selectedOfficesId = $this->selectedOfficesId->merge($this->region->offices->map->id);
        } else {
            $this->selectedUsersId   = collect([]);
            $this->selectedOfficesId = collect([]);
        }
        $this->emit('regionSelected', $this->region->id, $this->itsSelected);
        $this->updateIds();
    }

    public function anyOfficeSelected()
    {
        $this->itsSelected = $this->selectedOfficesId->isNotEmpty();
    }

    public function toggleOffice($officeId, bool $insert)
    {
        $office = Office::query()
            ->when($this->withTrashed, function($query) {
                $query->withTrashed();
            })
            ->with(['dailyNumbers' => function($query) {
                $query->when($this->withTrashed, function($query) {
                    $query->withTrashed();
                });
            }])->find($officeId);
        if ($insert) {
            $this->selectedOfficesId->push($office->id);
            $this->selectedUsersId = $this->selectedUsersId->merge($office->dailyNumbers->unique('user_id')->map->user_id);
        } else {
            $this->selectedOfficesId = $this->selectedOfficesId->filter(fn($officeId) => $officeId !== $office->id);
            $this->selectedUsersId = $this->selectedUsersId->filter( function ($id) use($office) {
                return !$office->dailyNumbers->unique('user_id')->map(function ($dailyNumber) {
                        return $dailyNumber->user_id;
                    })->filter()->contains($id);
                }
                
            );
        }
        $this->anyOfficeSelected();
        $this->updateIds();
    }

    public function toggleUser($userId, bool $insert, $officeId)
    {
        if ($insert) {
            $this->selectedUsersId->push($userId);
            $this->selectedOfficesId = $this->selectedOfficesId->merge($officeId)->unique();
        } else {
            $this->selectedUsersId = $this->selectedUsersId->filter(fn($id) => $userId !== $id);
        }
        $this->updateIds();

        $this->isAnyUserSelected();
    }

    private function getUserIds()
    {
        return $this->region->offices->map(function ($office) {
            return $office->dailyNumbers->unique('user_id')->map(function ($dailyNumber) {
                return $dailyNumber->user_id;
            })->filter();
        })->flatten();
    }

    public function updateIds()
    {
        $this->emitUp('updateIds', $this->region->id, $this->selectedOfficesId, $this->selectedUsersId);
    }

    public function isAnyUserSelected()
    {
        $this->itsSelected = $this->selectedUsersId->isNotEmpty();
    }
    
    public function sortOffices($sortBy, $sortDirection)
    {
        $region = $this->region === null ? $this->findRegion($this->regionId) : $this->region;

        $this->offices = $sortDirection === 'asc'
            ? $this->sortOfficesAsc($region->offices, $sortBy)
            : $this->sortOfficesDesc($region->offices, $sortBy);
    }

    public function setDateOrPeriod($date, $period)
    {
        $this->selectedDate = $date;
        $this->period       = $period;
        $this->region       = $this->findRegion($this->regionId);
    }

    private function sortOfficesAsc(Collection $offices, string $sortBy)
    {
        return $offices->sortBy(
            fn(Office $office) => $office->dailyNumbers->count() ? $office->dailyNumbers->sum($sortBy) : 0
        )->values();
    }

    private function sortOfficesDesc(Collection $offices, string $sortBy)
    {
        return $offices->sortByDesc(
            fn(Office $office) => $office->dailyNumbers->count() ? $office->dailyNumbers->sum($sortBy) : 0
        )->values();
    }

    private function findRegion($regionId)
    {
        return Region::query()
            ->when($this->withTrashed, function($query) {
                $query->withTrashed();
            })
            ->with([
                'offices' => function ($query) {
                    $query->when($this->withTrashed, function ($query) {
                        $query->withTrashed()
                            ->where(function ($query) {
                                $query->whereHas('dailyNumbers', function ($query) {
                                    $query->inPeriod($this->period, new Carbon($this->selectedDate))->withTrashed();
                                })
                                    ->whereNotNull('deleted_at');
                            })
                            ->orWhereNull('deleted_at');
                    })
                        ->with([
                            'dailyNumbers' => function ($query) {
                                $query
                                    ->when($this->withTrashed, fn($query) => $query->withTrashed())
                                    ->when(!$this->withTrashed, fn($query) => $query->has('user'))
                                    ->inPeriod($this->period, new Carbon($this->selectedDate));
                            },
                        ]);
                }
            ])
            ->find($regionId);
    }
}
