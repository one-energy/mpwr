<?php

namespace Tests\Feature\Castle\Rate;

use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DestroyRateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_destroy_an_rate()
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($departmentManager->id);
        $departmentManager->update(['department_id' => $department->id]);

        User::factory()->create(['role' => Role::SALES_REP]);
        User::factory()->create(['role' => Role::SETTER]);

        $rate = Rates::factory()->create([
            'department_id' => $department->id,
            'role'          => Role::SALES_REP,
        ]);

        $this->actingAs($departmentManager)
            ->delete(route('castle.rates.destroy', $rate->id))
            ->assertStatus(Response::HTTP_FOUND);

        $deleted = Rates::where('id', $rate->id)->get();

        $this->assertNotNull($deleted);
    }
}
