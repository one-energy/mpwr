<?php

namespace Tests\Feature\Castle\Rate;

use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class StoreRateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles_in_rates()
    {
        $setter = User::factory()->create([
            'role' => 'Setter',
        ]);

        $department = Department::factory()->create([
            'department_manager_id' => $setter->id,
        ]);

        $setter->department_id = $department->id;
        $setter->save();

        $this->actingAs($setter)
            ->get(route('castle.rates.create'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles_in_rates()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $this
            ->actingAs($departmentManager)
            ->get(route('castle.rates.create'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.rate.create');
    }

    /** @test */
    public function it_should_store_a_new_rate()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $data = [
            'name'          => 'rate',
            'time'          => 25,
            'rate'          => 2.5,
            'department_id' => $department->id,
            'role'          => 'Sales Rep',
        ];

        $this->actingAs($departmentManager);

        $this->post(route('castle.rates.store'), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('castle.rates.index'));
    }

    /** @test */
    public function it_shouldnt_store_a_repeated_rate()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $data = [
            'name'          => 'rate',
            'time'          => 25,
            'rate'          => 2.5,
            'department_id' => $department->id,
            'role'          => 'Sales Rep',
        ];

        Rates::factory()->create($data);

        $this->actingAs($departmentManager)
            ->post(route('castle.rates.store'), $data);

        $this->assertDatabaseCount('rates', 1);
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_rate()
    {
        $departmentManager                = User::factory()->create(['role' => 'Department Manager']);
        $department                       = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        $data = [
            'name'          => '',
            'time'          => '',
            'rate'          => '',
            'department_id' => '',
            'role'          => 'Sales Rep',
        ];

        $this->actingAs($departmentManager)
            ->post(route('castle.rates.store'), $data)
            ->assertSessionHasErrors([
                'name',
                'time',
                'rate',
                'department_id',
            ]);
    }
}
