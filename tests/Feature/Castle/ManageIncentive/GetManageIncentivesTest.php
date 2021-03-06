<?php

namespace Tests\Feature\Castle\ManageIncentive;

use App\Models\Department;
use App\Models\Incentive;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetManageIncentivesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_list_all_incentives()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $incentives = Incentive::factory()->count(6)->create([
            'department_id' => $department->id,
        ]);

        $this->actingAs($departmentManager);

        $response = $this->get(route('castle.incentives.index'));

        $response->assertStatus(200)
            ->assertViewIs('castle.incentives.index');

        foreach ($incentives as $incentive) {
            $response->assertSee($incentive->name);
        }
    }

    /** @test */
    public function it_should_block_access_to_incentives()
    {
        $regionManager = User::factory()->create(['role' => 'Region Manager']);
        $officeManager = User::factory()->create(['role' => 'Office Manager']);
        $setter        = User::factory()->create(['role' => 'Setter']);
        $salesRep      = User::factory()->create(['role' => 'Sales Rep']);

        $this->actingAs($regionManager)
            ->get(route('castle.incentives.index'))
            ->assertForbidden();

        $this->actingAs($officeManager)
            ->get(route('castle.incentives.index'))
            ->assertForbidden();

        $this->actingAs($setter)
            ->get(route('castle.incentives.index'))
            ->assertForbidden();

        $this->actingAs($salesRep)
            ->get(route('castle.incentives.index'))
            ->assertForbidden();
    }

    /** @test */
    public function it_should_allow_access_to_incentives()
    {
        $owner             = User::factory()->create(['role' => 'Owner']);
        $admin             = User::factory()->create(['role' => 'Admin']);
        $departmentManager = User::factory()->create(['role' => 'Department Manager']);

        $this->actingAs($owner)
            ->get(route('castle.incentives.index'))
            ->assertSuccessful();

        $this->actingAs($admin)
            ->get(route('castle.incentives.index'))
            ->assertSuccessful();

        $this->actingAs($departmentManager)
            ->get(route('castle.incentives.index'))
            ->assertSuccessful();
    }
}
