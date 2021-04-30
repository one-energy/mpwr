<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @property-read array $pills
 */
class NumberTrackerDetail extends Component
{
    use FullTable;

    public array $unselectedRegions          = [];

    public array $unselectedOffices          = [];

    public array $unselectedUserDailyNumbers = [];

    public string $period                    = 'm';

    public Collection $topTenTrackers;

    public $date;

    public $dateSelected;

    public string $selectedPill = 'hours';

    protected $listeners = ['sumTotalNumbers', 'loadingSumNumberTracker', 'updateLeaderBoard' => 'getUnselectedCollections'];

    public function mount()
    {
        $this->dateSelected = date('Y-m-d', time());
    }

    public function render()
    {
        $this->topTenTrackers = $this->getTopTenTrackers();
        return view('livewire.number-tracker.number-tracker-detail');
    }

    public function sortBy()
    {
        return "doors";
    }

    public function setPeriod($p)
    {
        $this->period = $p;
        $this->emit('setDateOrPeriod', $this->dateSelected, $this->period);
    }

    public function setDate()
    {
        $this->dateSelected = date('Y-m-d', strtotime($this->date));
        $this->emit('setDateOrPeriod', $this->dateSelected, $this->period);
    }

    public function getUnselectedCollections($unselectedRegions, $unselectedOffices, $unselectedUserDailyNumbers)
    {
        $this->unselectedRegions          = $unselectedRegions;
        $this->unselectedOffices          = $unselectedOffices;
        $this->unselectedUserDailyNumbers = $unselectedUserDailyNumbers;
    }

    public function getPillsProperty()
    {
        return ['doors', 'hours', 'sets', 'set sits', 'sg sits', 'set closes', 'sg closes'];
    }

    private function getTopTenTrackers()
    {
        if (!in_array($this->selectedPill, $this->pills)) {
            return collect();
        }

        $query = DailyNumber::query()
            ->with(['user' => fn($query) => $query->withTrashed()])
            ->with(['office' => function ($query) {
                $query->whereNotIn('region_id', $this->unselectedRegions);
            }]);

        if (user()->notHaveRoles(['Admin', 'Owner'])) {
            $query->whereHas(
                'user',
                fn (Builder $query) => $query->where('department_id', user()->department_id)
            );
        }

        // dd($this->unselectedRegions);
        return $query
            ->inPeriod($this->period, new Carbon($this->dateSelected))
            ->whereNotIn('office_id', $this->unselectedOffices)
            ->whereNotIn('user_id', $this->unselectedUserDailyNumbers)
            ->orderBy('total', 'desc')
            ->groupBy('user_id')
            ->select(
                DB::raw($this->getTotalRawQuery($this->getSluggedPill())),
                'user_id'
            )
            ->limit(10)
            ->get();
    }

    private function getSluggedPill()
    {
        return strtolower(Str::slug($this->selectedPill, '_'));
    }

    private function getTotalRawQuery(string $pill)
    {
        $rawQuery = sprintf('SUM(%s) as total', $pill);

        if ($pill === 'sg_sits') {
            $rawQuery = sprintf('SUM(sits + set_sits) as total');
        }

        if ($pill === 'sg_closes') {
            $rawQuery = sprintf('SUM(closes + set_closes) as total');
        }

        return $rawQuery;
    }
}
