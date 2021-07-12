<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\NumberTrackerDetail;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class GetTopTenTeamsTest extends TestCase
{
    use DatabaseTransactions;

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

        User::factory()->count(3)->create(['department_id' => $departmentOne->id]);
        User::factory()->count(2)->create(['department_id' => $departmentTwo->id]);

        Livewire::test(NumberTrackerDetail::class, [
            'selectedDepartment'          => $departmentOne->id,
            'selectedTeamLeaderboardPill' => 'accounts'
        ])
        ->assertSee('Team Leaderboard')
        ->assertSeeInOrder([$departmentOne->name, $departmentOne->users->count(), $departmentTwo->name, $departmentTwo->users->count()]);
    }

    private function createManager(): User
    {
        return User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
    }
}
