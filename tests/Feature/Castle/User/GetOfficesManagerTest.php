<?php

namespace Tests\Feature\Castle\User;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetOfficesManagerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_authenticated_to_get_offices_manager()
    {
        $this->get(route('getOfficesManager', 1))
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

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($john->id);

        $region = Region::factory()->create(['department_id' => $department->id]);

        collect([$mary, $zack])->each(fn(User $user) => $user->update(['department_id' => $department->id]));

        $zack->update(['department_id' => $department->id]);
        $joseph->update(['department_id' => $department->id]);

        $this
            ->actingAs($john)
            ->get(route('getOfficesManager', $region->id))
            ->assertSee($zack->first_name)
            ->assertSee($mary->first_name)
            ->assertDontSee($alice->first_name)
            ->assertDontSee($joseph->first_name);
    }
}