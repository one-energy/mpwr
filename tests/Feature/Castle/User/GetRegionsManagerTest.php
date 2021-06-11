<?php

namespace Tests\Feature\Castle\User;

use App\Models\Department;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetRegionsManagerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_authenticated_to_get_regions_manager()
    {
        $this->post(route('getRegionsManager', 1))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function it_should_return_users_that_have_region_manager_role_from_provided_department_id()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $mary = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $zack = User::factory()->create(['role' => Role::REGION_MANAGER]);

        $alice  = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $joseph = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($mary->id);

        $zack->update(['department_id' => $department->id]);
        $joseph->update(['department_id' => $department->id]);

        $this
            ->actingAs($john)
            ->post(route('getRegionsManager', $department->id))
            ->assertSee($zack->first_name)
            ->assertDontSee($alice->first_name)
            ->assertDontSee($joseph->first_name);
    }
}
