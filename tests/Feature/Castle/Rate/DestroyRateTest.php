<?php

namespace Tests\Feature\Castle\Rate;

use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class DestroyRateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_destroy_an_rate()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        User::factory()->create(['role' => 'Sales Rep']);
        User::factory()->create(['role' => 'Setter']);

        $rate = Rates::factory()->create([
            'department_id' => $department->id,
            'role'          => 'Sales Rep',
        ]);

        $this->actingAs($departmentManager)
            ->delete(route('castle.rates.destroy', $rate->id))
            ->assertStatus(Response::HTTP_FOUND);

        $deleted = Rates::where('id', $rate->id)->get();

        $this->assertNotNull($deleted);
    }
}
