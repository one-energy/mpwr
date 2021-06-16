<?php

namespace App\Http\Livewire\NumberTracker;

use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * @property-read array $leaderboardPills
 * @property-read array $teamLeaderboardPills
 */
class NumberTrackerDetail extends Component
{
    use FullTable;

    public array $unselectedRegions = [];

    public array $unselectedOffices = [];

    public array $unselectedUserDailyNumbers = [];

    public string $period = 'd';

    public bool $deleteds = false;

    public Collection $topTenTrackers;

    public Collection $topTenTeams;

    public $date;

    public $dateSelected;

    public string $selectedLeaderboardPill = 'hours worked';

    public string $selectedTeamLeaderboardPill = 'c.p.r';

    public int $selectedDepartment;

    protected $listeners = [
        'sumTotalNumbers',
        'loadingSumNumberTracker',
        'updateLeaderBoard'    => 'getUnselectedCollections',
        'onSelectedDepartment' => 'changeSelectedDepartment',
    ];

    public function mount()
    {
        $this->selectedDepartment = $this->getDepartmentId();

        $this->dateSelected = date('Y-m-d', time());
    }

    public function render()
    {
        $this->topTenTrackers = $this->getTopTenTrackers();
        $this->topTenTeams    = $this->getToTenTeams();

        return view('livewire.number-tracker.number-tracker-detail');
    }

    public function sortBy()
    {
        return 'doors';
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

    public function updateLeaderBoardCard(
        $unselectedRegions,
        $unselectedOffices,
        $unselectedUserDailyNumbers,
        $withDeleteds
    ) {
        $this->deleteds = $withDeleteds;
        $this->getUnselectedCollections($unselectedRegions, $unselectedOffices, $unselectedUserDailyNumbers);
    }

    public function getUnselectedCollections($unselectedRegions, $unselectedOffices, $unselectedUserDailyNumbers)
    {
        $this->unselectedRegions          = $unselectedRegions;
        $this->unselectedOffices          = $unselectedOffices;
        $this->unselectedUserDailyNumbers = $unselectedUserDailyNumbers;
    }

    public function changeSelectedDepartment(int $departmentId)
    {
        $this->selectedDepartment = $departmentId;
        $this->topTenTrackers     = $this->getTopTenTrackers();
    }

    public function getLeaderboardPillsProperty()
    {
        return ['hours worked', 'doors', 'hours knocked', 'sets', 'sats', 'set closes', 'closer sits', 'closes'];
    }

    public function getTeamLeaderboardPillsProperty()
    {
        return ['c.p.r', 'accounts'];
    }

    private function getTopTenTrackers()
    {
        if (!in_array($this->selectedLeaderboardPill, $this->leaderboardPills, true)) {
            return collect();
        }

        return DailyNumber::query()
            ->withTrashed()
            ->with([
                'user' => function ($query) {
                    $query->when($this->deleteds, function ($query) {
                        $query->withTrashed();
                    });
                },
            ])
            ->when(user()->notHaveRoles(['Admin', 'Owner']), function ($query) {
                $query->whereHas('user', function ($query) {
                    $query
                        ->where('department_id', user()->department_id)
                        ->withTrashed();
                });
            })
            ->when(!$this->deleteds, function ($query) {
                $query->has('user');
            })
            ->inPeriod($this->period, new Carbon($this->dateSelected))
            ->whereHas('office', function ($query) {
                $query->whereNotIn('region_id', $this->unselectedRegions);
            })
            ->whereNotIn('office_id', $this->unselectedOffices)
            ->whereNotIn('user_id', $this->unselectedUserDailyNumbers)
            ->orderBy('total', 'desc')
            ->groupBy('user_id')
            ->select(
                DB::raw($this->getTotalRawQuery($this->getSluggedPill($this->selectedLeaderboardPill))),
                'user_id'
            )
            ->limit(10)
            ->get();
    }

    private function getToTenTeams(): Collection
    {
        if (!in_array($this->selectedTeamLeaderboardPill, $this->teamLeaderboardPills, true)) {
            return collect();
        }

        if ($this->getSluggedPill($this->selectedTeamLeaderboardPill) === 'cpr') {
            return $this->getTopTenTeamsByCpr();
        }

        return $this->getToTenTeamsByAccount();
    }

    private function getSluggedPill(string $value)
    {
        return strtolower(Str::slug($value, '_'));
    }

    private function getTotalRawQuery(string $pill)
    {
        $rawQuery = sprintf('SUM(%s) as total', $pill);

        return $rawQuery;
    }

    private function getDepartmentId()
    {
        return user()->hasAnyRole(['Admin', 'Owner'])
            ? Department::oldest('name')->first()->id
            : (user()->department_id ?? 0);
    }

    private function getToTenTeamsByAccount(): Collection
    {
        return Department::query()
            ->when($this->deleteds, function ($query) {
                $query->withTrashed()
                    ->withCount(['users as total' => fn($query) => $query->withTrashed()]);
            })
            ->when(!$this->deleteds, fn($query) => $query->withCount('users as total'))
            ->latest('total')
            ->limit(10)
            ->get();
    }

    private function getTopTenTeamsByCpr(): Collection
    {
        return Department::query()
            ->with([
                'offices' => function ($query) {
                    $query->whereHas('users', fn ($query) => $query->where('role', 'Sales Rep'));
                },
                'offices.dailyNumbers' => function ($query) {
                    $query
                        ->inPeriod($this->period, new Carbon($this->dateSelected))
                        ->groupBy(['user_id', 'office_id'])
                        ->select(['user_id', 'office_id', DB::raw('SUM(closes) as closes_total')]);
                },
            ])
            ->withCount(['users as sales_rep_total' => fn($query) => $query->where('role', 'Sales Rep')])
            ->limit(10)
            ->get()
            ->map(function (Department $department) {
                if ($department->offices->isEmpty()) {
                    return $this->buildDepartment($department);
                }

                $total = 0;

                if ($department->sales_rep_total > 0) {
                    $total = $this->getSumOfClosesTotal($department) / $department->sales_rep_total;
                }

                return $this->buildDepartment($department, $total);
            })
            ->sortByDesc('total');
    }

    private function getSumOfClosesTotal(Department $department)
    {
        return $department
            ->offices
            ->filter(fn (Office $office) => $office->dailyNumbers->isNotEmpty())
            ->map
            ->dailyNumbers
            ->flatten()
            ->sum('closes_total');
    }

    private function buildDepartment(Department $department, int $total = 0)
    {
        return (new Department([
            'name'  => $department->name,
        ]))->forceFill([
            'id'         => $department->id,
            'deleted_at' => $department->deleted_at,
            'total'      => $total,
        ]);
    }
}
