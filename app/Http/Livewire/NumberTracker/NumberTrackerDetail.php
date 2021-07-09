<?php

namespace App\Http\Livewire\NumberTracker;

use App\Enum\Role;
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

    public array $selectedUsersIds = [];

    public array $selectedOfficesIds = [];

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
        'toggleDelete',
        'updateNumbers',
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
        $this->topTenTeams    = $this->getTopTenTeams();

        return view('livewire.number-tracker.number-tracker-detail');
    }

    public function sortBy()
    {
        return 'doors';
    }

    public function toggleDelete($value)
    {
        $this->deleteds = $value;
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

    public function updateNumbers($payload)
    {
        $this->selectedUsersIds   = $payload['users'];
        $this->selectedOfficesIds = $payload['offices'];
    }

    private function getTopTenTrackers()
    {
        if (!in_array($this->selectedLeaderboardPill, $this->leaderboardPills, true)) {
            return collect();
        }

        return DailyNumber::query()
            ->withTrashed()
            ->with('user', fn($query) => $query->withTrashed())
            ->whereIn('user_id', $this->selectedUsersIds)
            ->whereIn('office_id', $this->selectedOfficesIds)
            ->inPeriod($this->period, new Carbon($this->date))
            ->orderBy('total', 'desc')
            ->groupBy('user_id')
            ->select(
                DB::raw($this->getTotalRawQuery($this->getSluggedPill($this->selectedLeaderboardPill))),
                'user_id'
            )
            ->limit(10)
            ->get();
    }

    private function getTopTenTeams(): Collection
    {
        if (!in_array($this->selectedTeamLeaderboardPill, $this->teamLeaderboardPills, true)) {
            return collect();
        }

        if ($this->getSluggedPill($this->selectedTeamLeaderboardPill) === 'cpr') {
            return $this->getTopTenTeamsByCpr();
        }

        return $this->getTopTenTeamsByAccount();
    }

    private function getSluggedPill(string $value)
    {
        return strtolower(Str::slug($value, '_'));
    }

    private function getTotalRawQuery(string $pill)
    {
        return sprintf('SUM(%s) as total', $pill);
    }

    private function getDepartmentId()
    {
        return user()->hasAnyRole([Role::ADMIN, Role::OWNER])
            ? Department::oldest('name')->first()->id
            : (user()->department_id ?? 0);
    }

    private function getTopTenTeamsByAccount(): Collection
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
        $relationName = $this->deleteds ? 'officesTrashedParents' : 'offices';

        return Department::query()
            ->with([
                $relationName                  => function ($query) {
                    $query
                        ->when($this->deleteds, function ($query) {
                            $query
                                ->withTrashed()
                                ->whereHas('users', function ($query) {
                                    $query->withTrashed()->where('role', 'Sales Rep');
                                });
                        })
                        ->when(!$this->deleteds, function ($query) {
                            $query->whereHas('users', fn($query) => $query->where('role', 'Sales Rep'));
                        });
                },
                "{$relationName}.dailyNumbers" => function ($query) {
                    $query
                        ->when($this->deleteds, fn($query) => $query->withTrashed())
                        ->inPeriod($this->period, new Carbon($this->dateSelected))
                        ->groupBy(['user_id', 'office_id'])
                        ->select(['user_id', 'office_id', DB::raw('SUM(closes) as closes_total')]);
                },
            ])
            ->when($this->deleteds, function ($query) {
                $query->withTrashed()
                    ->withCount([
                        'users as sales_rep_total' => function ($query) {
                            $query->withTrashed()->where('role', 'Sales Rep');
                        },
                    ]);
            })
            ->when(!$this->deleteds, function ($query) {
                $query->withCount([
                    'users as sales_rep_total' => fn($query) => $query->where('role', 'Sales Rep'),
                ]);
            })
            ->limit(10)
            ->get()
            ->map(function (Department $department) use ($relationName) {
                if ($department->{$relationName}->isEmpty()) {
                    return $this->buildDepartment($department);
                }

                $total = 0;

                if ($department->sales_rep_total > 0) {
                    $total = $this->getSumOfClosesTotal($department, $relationName) / $department->sales_rep_total;
                }

                return $this->buildDepartment($department, $total);
            })
            ->sortByDesc('total');
    }

    private function getSumOfClosesTotal(Department $department, string $relationName)
    {
        return $department
            ->{$relationName}
            ->filter(fn(Office $office) => $office->dailyNumbers->isNotEmpty())
            ->map
            ->dailyNumbers
            ->flatten()
            ->sum('closes_total');
    }

    private function buildDepartment(Department $department, int $total = 0)
    {
        return (new Department([
            'name' => $department->name,
        ]))->forceFill([
            'id'         => $department->id,
            'deleted_at' => $department->deleted_at,
            'total'      => $total,
        ]);
    }
}
