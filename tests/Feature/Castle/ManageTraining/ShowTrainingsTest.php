<?php

namespace Tests\Feature\Castle\ManageTraining;

use App\Enum\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Builders\TrainingSectionBuilder;
use Tests\TestCase;

class ShowTrainingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_render_manage_trainings_index_view()
    {
        $john       = User::factory()->create(['role' => Role::ADMIN]);
        $department = Department::factory()->create(['department_manager_id' => $john->id]);
        $section    = TrainingSectionBuilder::build()->withDepartment($department)->withRegion()->save()->get();

        $this->actingAs($john)
            ->get(route('castle.manage-trainings.index'))
            ->assertViewIs('castle.manage-trainings.index')
            ->assertSuccessful()
            ->assertOk()
            ->assertSee($section->title);
    }

    /** @test */
    public function it_should_redirect_department_manager_or_region_manager_to_castle_home_if_dont_have_department_id()
    {
        $john = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $mary = User::factory()->create(['role' => Role::REGION_MANAGER]);

        $this->actingAs($john)
            ->get(route('castle.manage-trainings.index'))
            ->assertRedirect(route('castle.dashboard'))
            ->assertSessionHas('alert');

        $this->actingAs($mary)
            ->get(route('castle.manage-trainings.index'))
            ->assertRedirect(route('castle.dashboard'))
            ->assertSessionHas('alert');
    }
}
