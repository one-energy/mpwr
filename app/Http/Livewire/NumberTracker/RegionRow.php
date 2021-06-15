<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\Office;
use App\Models\Region;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class RegionRow extends Component
{
    public int $regionId;

    public ?Region $region;

    public string $period;

    public string $selectedDate;

    public bool $withTrashed;

    public Collection $offices;

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

    public function anyOfficeSelected()
    {
        $this->itsSelected = $this->getIdsFromCache($this->getCacheKey('offices'))->isNotEmpty();
    }

    public function isAnyUserSelected()
    {
        $this->itsSelected = $this->getIdsFromCache($this->getCacheKey('users'))->isNotEmpty();
    }

    public function sortOffices($sortBy, $sortDirection)
    {
        $region = $this->region ?? $this->findRegion($this->regionId);

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

    public function selectRegion()
    {
        if ($this->itsSelected) {
            $this->setRegionUsersIds();
            $this->setRegionOfficesIds();
        } else {
            $this->detachRegionUsersIds();
            $this->detachRegionOfficesIds();
        }
        $this->emit('regionSelected', $this->region->id, $this->itsSelected);
        $this->updateIds();
    }

    public function toggleOffice(int $officeId, bool $insert)
    {
        $office = Office::query()
            ->when($this->withTrashed, function($query) {
                $query->withTrashed();
            })
            ->find($officeId)
            ->load(['dailyNumbers' => function ($query) {
                $query->inPeriod($this->period, new Carbon($this->selectedDate))
                    ->inPeriod($this->period, new Carbon($this->selectedDate))->when($this->withTrashed, fn ($query) => $query->withTrashed())
                    ->when(!$this->withTrashed, fn ($query) => $query->has('user'));
            }]);

        if ($insert) {
            $this->attachIn('offices', $office->id);
            $this->attachIn('users', $office->dailyNumbers->unique('user_id')->map->user_id);
        } else {
            $usersIds = $office->dailyNumbers->unique('user_id')->map->user_id;

            $this->detachFrom('offices', $office->id);
            $this->detachFrom('offices', $usersIds);
        }
        $this->anyOfficeSelected();
        $this->updateIds();
    }

    public function toggleUser($userId, bool $insert, $officeId)
    {
        if ($insert) {
            $this->attachIn('users', $userId);
            $this->attachIn('offices', $officeId);
        } else {
            $this->detachFrom('users', $userId);
        }
        $this->updateIds();

        $this->isAnyUserSelected();
    }

    public function updateIds()
    {
        $this->emitUp('updateIds');
    }

    private function findRegion($regionId)
    {
        return Region::query()
            ->when($this->withTrashed, function($query) {
                $query->withTrashed();
            })
            ->find($regionId)
            ->load([
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
            ]);
    }

    private function getCacheKey(string $key)
    {
        return sprintf('%s-region-%s-ids', user()->id, $key);
    }

    private function getIdsFromCache(string $key): Collection
    {
        $payload  = Cache::get($key);

        if ($payload === null || $payload === '') {
            return collect();
        }

        return collect(json_decode($payload, true));
    }

    private function setRegionUsersIds()
    {
        $cacheKey = $this->getCacheKey('users');
        $ids      = $this->getIdsFromCache($cacheKey);
        $ids      = $ids->merge($this->getUniqueUsersIds());

        Cache::put($cacheKey, json_encode($ids->toArray()));
    }

    private function setRegionOfficesIds()
    {
        $cacheKey = $this->getCacheKey('offices');
        $ids      = $this->getIdsFromCache($cacheKey);
        $ids      = $ids->merge($this->region->offices->map->id);

        Cache::put($cacheKey, json_encode($ids->toArray()));
    }

    private function detachRegionUsersIds()
    {
        $cacheKey = $this->getCacheKey('users');
        $ids      = $this->getIdsFromCache($cacheKey);
        $ids      = $ids->filter(fn($id) => !in_array($id, $this->getUniqueUsersIds()->toArray()));

        Cache::put($cacheKey, json_encode($ids), 60);
    }

    private function detachRegionOfficesIds()
    {
        $cacheKey = $this->getCacheKey('offices');
        $ids      = $this->getIdsFromCache($cacheKey);
        $ids      = $ids->filter(
            fn($id) => !in_array($id, collect($this->region->offices->map->id)->toArray())
        );

        Cache::put($cacheKey, json_encode($ids), 60);
    }

    private function attachIn(string $type, $id)
    {
        $cacheKey = $this->getCacheKey($type);
        $ids      = $this->getIdsFromCache($cacheKey);
        $ids      = $ids->merge($id);

        Cache::put($cacheKey, json_encode($ids->toArray()));
    }

    private function detachFrom(string $type, $idsToRemove)
    {
        $idsList  = collect($idsToRemove)->flatten()->toArray();
        $cacheKey = $this->getCacheKey($type);
        $ids      = $this->getIdsFromCache($cacheKey);
        $ids      = $ids->filter(fn ($id) => !in_array($id, $idsList));

        Cache::put($cacheKey, json_encode($ids->toArray()));
    }

    private function getUniqueUsersIds()
    {
        return $this->region->offices->map(function ($office) {
            return $office->dailyNumbers->unique('user_id')->map(function ($dailyNumber) {
                return $dailyNumber->user_id;
            })->filter();
        })->flatten();
    }
}
