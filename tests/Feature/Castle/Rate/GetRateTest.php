<?php

namespace Tests\Feature\Castle\Rate;

use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use App\Enum\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetRateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_list_all_rates()
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($departmentManager->id);
        $departmentManager->update(['department_id' => $department->id]);

        $rates = Rates::factory()->count(6)->create([
            'department_id' => $department->id,
            'role'          => Role::SALES_REP,
        ]);

        $response = $this->actingAs($departmentManager)
            ->get(route('castle.rates.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.rate.index');

        foreach ($rates as $rate) {
            $response->assertSee($rate->name);
        }
    }
}
