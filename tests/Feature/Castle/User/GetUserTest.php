<?php

namespace Tests\Feature\Castle\User;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GetUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_see_users()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $dummies = User::factory()->times(2)->create(['role' => Role::SETTER]);

        $this->actingAs($john)
            ->get(route('castle.users.index'))
            ->assertViewIs('castle.users.index')
            ->assertSee($dummies->first()->full_name)
            ->assertSee($dummies->last()->full_name);
    }

    /** @test */
    public function it_should_forbidden_see_users_page_if_authenticated_user_not_have_correct_role()
    {
        $setterManager = User::factory()->create(['role' => Role::SETTER]);

        $this->actingAs($setterManager)
            ->get(route('castle.users.index'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_return_region_managers_from_provided_department()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Collection $managers */
        $manager01  = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $manager02  = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $department = Department::factory()->create();

        $manager01->update(['department_id' => $department->id]);

        $response = $this->actingAs($john)
            ->postJson(route('getRegionsManager', ['departmentId' => $department->id]))
            ->assertSuccessful()
            ->assertOk()
            ->decodeResponseJson();

        $this->assertCount(1, $response->json());
        $this->assertEquals($manager01->full_name, $response->json()[0]['full_name']);
        $this->assertNotContains($manager02->full_name, $response->json()[0]);
    }

    /** @test */
    public function it_should_return_office_managers_from_provided_region()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Collection $managers */
        $manager01  = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $manager02  = User::factory()->create(['role' => Role::OFFICE_MANAGER]);
        $department = Department::factory()->create();
        $region     = Region::factory()->create([
            'region_manager_id' => User::factory()->create(['role' => Role::REGION_MANAGER])->id,
            'department_id'     => $department->id
        ]);

        $manager01->update(['department_id' => $department->id]);

        $response = $this->actingAs($john)
            ->getJson(route('getOfficesManager', ['region' => $region->id]))
            ->assertSuccessful()
            ->assertOk()
            ->decodeResponseJson();

        $this->assertCount(1, $response->json());
        $this->assertEquals($manager01->full_name, $response->json()[0]['full_name']);
        $this->assertNotContains($manager02->full_name, $response->json()[0]);
    }
}
