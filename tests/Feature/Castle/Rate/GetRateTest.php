<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GetRateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_list_all_rates()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $rates = Rates::factory()->count(6)->create([
            'department_id' => $department->id,
            'role'          => 'Sales Rep',
        ]);

        $response = $this->actingAs($departmentManager)
            ->get('castle/rates')
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.rate.index');

        foreach ($rates as $rate) {
            $response->assertSee($rate->name);
        }
    }
}
