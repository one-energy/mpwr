<?php

namespace Tests\Feature\Livewire\NumberTracker\NumberTrackerDetail;

use App\Enum\Role;
use App\Http\Livewire\NumberTracker\NumberTrackerDetail;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class NumberTrackerDetailTest extends TestCase
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

        $this->createManager();
    }

    /** @test */
    public function it_should_toggle_deleteds_when_emit_event()
    {
        $department = $this->createDepartment();

        $this->actingAs($this->admin);

        Livewire::test(NumberTrackerDetail::class, ['selectedDepartment' => $department->id])
            ->assertSet('deleteds', false)
            ->emit('toggleDelete', true)
            ->assertSet('deleteds', true);
    }

    /** @test */
    public function it_should_change_date()
    {
        $department = $this->createDepartment();

        $this->actingAs($this->admin);

        Livewire::test(NumberTrackerDetail::class, ['selectedDepartment' => $department->id])
            ->assertSet('dateSelected', Carbon::now()->toDateString())
            ->set('date', Carbon::yesterday()->toDateString())
            ->call('setDate')
            ->assertSet('dateSelected', Carbon::yesterday()->toDateString())
            ->assertEmitted('setDateOrPeriod');
    }

    private function createDepartment(?User $manager = null): Department
    {
        $manager = $manager ?? $this->departmentManager;

        return Department::factory()->create(['department_manager_id' => $manager->id]);
    }

    private function createManager(): void
    {
        /** @var User departmentManager */
        $this->departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
    }
}
