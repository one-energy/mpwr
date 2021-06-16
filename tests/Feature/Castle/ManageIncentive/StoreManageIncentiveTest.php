<?php

namespace Tests\Feature\Castle\ManageIncentive;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class StoreManageIncentiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $setter = User::factory()->create([
            'role' => 'setter',
        ]);

        $department = Department::factory()->create([
            'department_manager_id' => $setter->id,
        ]);

        $setter->department_id = $department->id;
        $setter->save();

        $this->actingAs($setter)
            ->get(route('castle.incentives.create'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs($departmentManager)
            ->get(route('castle.incentives.create'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.incentives.create');
    }

    /** @test */
    public function it_should_store_a_new_incentive()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $data = [
            'number_installs' => 48,
            'name'            => 'Incentive',
            'installs_needed' => 67,
            'kw_needed'       => 100,
            'department_id'   => $department->id,
        ];

        $this->actingAs($departmentManager)
            ->post(route('castle.incentives.store'), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('castle.incentives.index'));
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_incentive()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs($departmentManager)
            ->post(route('castle.incentives.store'), [])
            ->assertSessionHasErrors([
                'number_installs',
                'name',
                'installs_needed',
                'kw_needed',
            ]);
    }
}
