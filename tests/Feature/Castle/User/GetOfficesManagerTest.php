<?php

namespace Tests\Feature\Castle\User;

use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetOfficesManagerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_authenticated_to_get_offices_manager()
    {
        $this->post(route('getOfficesManager', 1))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function it_should_return_users_that_have_office_manager_role_from_provided_region_id()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $zack = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $alice  = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $joseph = User::factory()->create(['role' => Role::REGION_MANAGER]);

        $department = Department::factory()->create(['department_manager_id' => $mary->id]);
        $region     = Region::factory()->create(['department_id' => $department->id]);

        collect([$mary, $zack])->each(fn(User $user) => $user->update(['department_id' => $department->id]));

        $zack->update(['department_id' => $department->id]);
        $joseph->update(['department_id' => $department->id]);

        $this
            ->actingAs($john)
            ->post(route('getOfficesManager', $region->id))
            ->assertSee($zack->first_name)
            ->assertSee($mary->first_name)
            ->assertDontSee($alice->first_name)
            ->assertDontSee($joseph->first_name);
    }
}
