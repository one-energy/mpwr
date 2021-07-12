<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\NumberTrackerDetail;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\Builders\OfficeBuilder;
use Tests\Builders\RegionBuilder;
use Tests\TestCase;

class GetTopTenTeamsTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    private User $admin;

    /** @var User */
    private User $departmentManager;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var User admin */
        $this->admin = User::factory()->create(['role' => Role::ADMIN]);

        /** @var User departmentManager */
        $this->departmentManager = $this->createManager();
    }
   
    /** @test */
    public function it_should_show_order_teams_by_account()
    {
        $this->actingAs($this->admin);

        $departmentOne = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);
        $departmentTwo = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);

        User::factory()->count(3)->create(['department_id' => $departmentOne->id]);
        User::factory()->count(2)->create(['department_id' => $departmentTwo->id]);

        Livewire::test(NumberTrackerDetail::class, [
            'selectedDepartment'          => $departmentOne->id,
            'selectedTeamLeaderboardPill' => 'accounts'
        ])
        ->assertSee('Team Leaderboard')
        ->assertSeeInOrder([$departmentOne->name, $departmentOne->users->count(), $departmentTwo->name, $departmentTwo->users->count()]);
    }

    /** @test */
    public function it_should_show_order_teams_by_account_deleteds()
    {
        $this->actingAs($this->admin);

        $departmentOne = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);
        $departmentTwo = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);

        User::factory()->count(2)->create(['department_id' => $departmentOne->id]);
        User::factory()->count(3)->create(['department_id' => $departmentTwo->id]);

        Livewire::test(NumberTrackerDetail::class, [
            'selectedDepartment'          => $departmentOne->id,
            'selectedTeamLeaderboardPill' => 'accounts'
        ])
        ->assertSeeInOrder(['Team Leaderboard', $departmentOne->name, $departmentOne->users->count()])
        ->emit('toggleDelete', true)
        ->assertSeeInOrder([$departmentTwo->name, $departmentTwo->users->count(), $departmentOne->name, $departmentOne->users->count()]);
    }
   
    /** @test */
    public function it_should_show_order_teams_by_c_p_r()
    {
        $this->actingAs($this->admin);

        $departmentOne = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);
        $departmentTwo = Department::factory()->create(['department_manager_id' => $this->departmentManager->id]);

        $regionOne = RegionBuilder::build()->withDepartment($departmentOne)->save()->get();
        $officeOne = OfficeBuilder::build()->region($regionOne)->save()->get();

        $jhon = User::factory()->create([
            'role'          => Role::SALES_REP,
            'department_id' => $departmentOne->id,
            'office_id'     => $officeOne->id
        ]);
        $mary = User::factory()->create([
            'role'          => Role::SALES_REP,
            'department_id' => $departmentTwo->id,
            'office_id'     => $officeOne->id
        ]);

        $this->createDailyNumber($jhon, $officeOne, ['date' => today(), 'closes' => 5]);
        $this->createDailyNumber($mary, $officeOne, ['date' => today(), 'closes' => 10]);

        $regionTwo = RegionBuilder::build()->withDepartment($departmentTwo)->save()->get();
        $officeTwo = OfficeBuilder::build()->region($regionTwo)->save()->get();

        $alan = User::factory()->create([
            'role'          => Role::SALES_REP,
            'department_id' => $departmentOne->id,
            'office_id'     => $officeTwo->id
        ]);

        $luke = User::factory()->create([
            'role'          => Role::SALES_REP,
            'department_id' => $departmentTwo->id,
            'office_id'     => $officeTwo->id
        ]);

        $this->createDailyNumber($alan, $officeOne, ['date' => today(), 'closes' => 10]);
        $this->createDailyNumber($luke, $officeOne, ['date' => today(), 'closes' => 15]);

        Livewire::test(NumberTrackerDetail::class, [
            'selectedDepartment'          => $departmentOne->id,
            'selectedTeamLeaderboardPill' => 'accounts'
        ])
        ->assertSeeInOrder([
            'Team Leaderboard', 
            $departmentTwo->name, 
            7, 
            $departmentOne->name, 
            12,
        ]);
    }

    private function createManager(): User
    {
        return User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
    }

    private function createDailyNumber(User $user, Office $office, array $attributes = []): DailyNumber
    {
        $defaultAttr = [
            'user_id'   => $user->id,
            'office_id' => $office->id,
        ];

        return DailyNumber::factory()->create(array_merge($defaultAttr, $attributes));
    }
}
