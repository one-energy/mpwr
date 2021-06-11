<?php

namespace Tests\Feature\Castle\Department;

use App\Models\Department;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetDepartmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_departments()
    {
        $admin                = User::factory()->create(['role' => Role::ADMIN]);
        $departmentManagerOne = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $departmentManagerTwo = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $departmentOne */
        $departmentOne = Department::factory()->create();
        $departmentOne->managers()->attach($departmentManagerOne->id);

        /** @var Department $departmentTwo */
        $departmentTwo = Department::factory()->create();
        $departmentTwo->managers()->attach($departmentManagerTwo->id);

        $this
            ->actingAs($admin)
            ->get(route('castle.departments.index'))
            ->assertStatus(200)
            ->assertViewIs('castle.departments.index')
            ->assertSee($departmentManagerOne->name)
            ->assertSee($departmentManagerTwo->name);
    }

    /** @test */
    public function it_should_block_access_to_department()
    {
        $departmentManager = User::factory()->create(['role' => 'Department Manager']);
        $regionManager     = User::factory()->create(['role' => 'Region Manager']);
        $officeManager     = User::factory()->create(['role' => 'Office Manager']);
        $setter            = User::factory()->create(['role' => 'Setter']);
        $salesRep          = User::factory()->create(['role' => 'Sales Rep']);

        $this->actingAs($departmentManager)
            ->get(route('castle.departments.index'))
            ->assertForbidden();

        $this->actingAs($regionManager)
            ->get(route('castle.departments.index'))
            ->assertForbidden();

        $this->actingAs($officeManager)
            ->get(route('castle.departments.index'))
            ->assertForbidden();

        $this->actingAs($setter)
            ->get(route('castle.departments.index'))
            ->assertForbidden();

        $this->actingAs($salesRep)
            ->get(route('castle.departments.index'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_allow_access_to_department()
    {
        $owner = User::factory()->create(['role' => 'Owner']);
        $admin = User::factory()->create(['role' => 'Admin']);

        $this->actingAs($owner)
            ->get(route('castle.departments.index'))
            ->assertSuccessful();

        $this->actingAs($admin)
            ->get(route('castle.departments.index'))
            ->assertSuccessful();
    }
}
