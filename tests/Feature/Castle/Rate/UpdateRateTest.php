<?php

namespace Tests\Feature\Castle\Rate;

use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateRateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_show_the_edit_form_for_top_level_roles()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $rate = Rates::factory()->create([
            'department_id' => $department->id,
            'role'          => 'Sales Rep',
        ]);

        $this->actingAs($departmentManager)
            ->get(route('castle.rates.edit', ['rate' => $rate->id]))
            ->assertStatus(200)
            ->assertViewIs('castle.rate.edit');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this->actingAs(User::factory()->create(['role' => 'Setter']));

        $rate = Rates::factory()->create([
            'department_id' => $department->id,
            'role'          => 'Sales Rep',
        ]);

        $this->get(route('castle.rates.edit', ['rate' => $rate->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_update_an_rate()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $rate       = Rates::factory()->create([
            'department_id' => $department->id,
            'rate'          => 2.1,
            'role'          => 'Sales Rep',
        ]);
        $data       = $rate->toArray();
        $updateRate = array_merge($data, ['name' => 'rate Edited']);

        $this->actingAs($departmentManager)
            ->put(route('castle.rates.update', $rate->id), $updateRate)
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('rates', [
            'id'   => $rate->id,
            'name' => 'rate Edited',
        ]);
    }
}
