<?php

namespace Tests\Feature\Castle\ManageIncentive;

use App\Models\Department;
use App\Models\Incentive;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateManageIncentiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_show_the_edit_form_for_top_level_roles()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $incentive = Incentive::factory()->create();

        $this->actingAs($departmentManager)
            ->get(route('castle.incentives.edit', $incentive->id))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.incentives.edit');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {

        $incentive = Incentive::factory()->create();

        $this->actingAs(User::factory()->create(['role' => 'Setter']))
            ->get(route('castle.incentives.edit', $incentive->id))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_update_an_incentive()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $incentive       = Incentive::factory()->create([
            'name'          => 'Incentive',
            'department_id' => $department->id,
        ]);
        $data            = $incentive->toArray();
        $updateIncentive = array_merge($data, ['name' => 'Incentive Edited']);

        $this->actingAs($departmentManager)
            ->put(route('castle.incentives.update', $incentive->id), $updateIncentive)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('incentives', [
            'id'   => $incentive->id,
            'name' => 'Incentive Edited',
        ]);
    }
}
