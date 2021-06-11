<?php

namespace Tests\Feature\Castle\ManageIncentive;

use App\Models\Department;
use App\Models\Incentive;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DestroyManageIncentiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_destroy_an_incentive()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $incentive = Incentive::factory()->create();

        $this->actingAs($departmentManager)
            ->delete(route('castle.incentives.destroy', $incentive->id))
            ->assertStatus(Response::HTTP_FOUND);

        $deleted = Incentive::where('id', $incentive->id)->get();

        $this->assertNotNull($deleted);
    }
}
