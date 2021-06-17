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

    public string $sortBy = 'hours_worked';

    public string $sortDirection = 'asc';

    protected $listeners = [
        'toggleOffice',
        'toggleUser',
        'setDateOrPeriod',
        'sorted' => 'sortOffices',
    ];

    public function mount()
    {
        $this->region = null;

        $this->sortOffices($this->sortBy, $this->sortDirection);
    }

    public function render()
    {
        $this->region = $this->findRegion($this->regionId);

        return view('livewire.number-tracker.region-row');
    }

    public function hydrateRegion()
    {
        $this->region->load($this->getEagerLoadingRelation());
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

        if ($this->itsOpen) {
            $this->sortOffices($this->sortBy, $this->sortDirection);
        }
    }

    public function anyOfficeSelected()
    {
        $cacheIds  = $this->getIdsFromCache($this->getCacheKey('offices'));
        $officeIds = $this->offices->map->id;

        $this->itsSelected = $cacheIds->contains(
            fn($officeId) => in_array($officeId, $officeIds->toArray())
        );
    }

    public function isAnyUserSelected()
    {
        $cacheIds = $this->getIdsFromCache($this->getCacheKey('users'));
        $userIds  = collect($this->offices)
            ->lazy()
            ->map(fn(Office $office) => $office->dailyNumbers->unique('user_id')->pluck('user_id'))
            ->flatten();

        $this->itsSelected = $cacheIds->contains(
            fn($userId) => in_array($userId, $userIds->toArray())
        );
    }

    public function sortOffices($sortBy, $sortDirection)
    {
        $this->sortBy        = $sortBy;
        $this->sortDirection = $sortDirection;
        $this->region        = $this->findRegion($this->regionId);

        $this->offices = $sortDirection === 'asc'
            ? $this->sortOfficesAsc($this->region->offices, $this->sortBy)
            : $this->sortOfficesDesc($this->region->offices, $this->sortBy);
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
            fn(Office $office) => $office->dailyNumbers->isNotEmpty() ? $office->dailyNumbers->sum($sortBy) : 0
        )->values();
    }

    private function sortOfficesDesc(Collection $offices, string $sortBy)
    {
        return $offices->sortByDesc(
            fn(Office $office) => $office->dailyNumbers->isNotEmpty() ? $office->dailyNumbers->sum($sortBy) : 0
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
        $office = $this->region->offices->firstWhere('id', $officeId);

        if ($insert) {
            $this->attachIn('offices', $office->id);
            $this->attachIn('users', $office->dailyNumbers->unique('user_id')->map->user_id);
        } else {
            $this->detachFrom('offices', $office->id);
            $this->detachFrom('users', $office->dailyNumbers->unique('user_id')->map->user_id);
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
            ->when($this->withTrashed, fn($query) => $query->withTrashed())
            ->find($regionId)
            ->load($this->getEagerLoadingRelation());
    }

    private function getCacheKey(string $key)
    {
        return sprintf('%s-region-%s-ids', user()->id, $key);
    }

    private function getIdsFromCache(string $key): Collection
    {
        $payload = Cache::get($key);

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
        $ids      = $ids->merge($this->getOfficesIds());

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
        $ids      = $ids->filter(fn($id) => !in_array($id, $idsList));

        Cache::put($cacheKey, json_encode($ids->toArray()));
    }

    private function getUniqueUsersIds()
    {
        return $this->region->offices->map(function ($office) {
            return $office->dailyNumbers->unique('user_id')->map->user_id->filter();
        })->flatten();
    }

    private function getOfficesIds()
    {
        return $this->region->offices->map->id;
    }

    private function getEagerLoadingRelation(): array
    {
        return [
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
        ];
    }
}
