<?php

namespace Tests\Feature\Castle\ManageIncentive;

use App\Models\Incentive;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DestroyManageIncentiveTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_destroy_an_incentive()
    {
        [$departmentManager] = $this->createVP();

        $incentive = Incentive::factory()->create();

        $this->actingAs($departmentManager)
            ->delete(route('castle.incentives.destroy', $incentive->id))
            ->assertStatus(Response::HTTP_FOUND);

        $deleted = Incentive::where('id', $incentive->id)->get();

        $this->assertNotNull($deleted);
    }
}
