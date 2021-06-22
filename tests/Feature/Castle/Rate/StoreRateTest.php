<?php

namespace Tests\Feature\Castle\Rate;

use App\Enum\Role;
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
        $setter = User::factory()->create(['role' => Role::SETTER]);
        $ann    = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($ann->id);
        $setter->update(['department_id' => $department->id]);

        $this->actingAs($setter)
            ->get(route('castle.rates.create'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles_in_rates()
    {
        [$departmentManager] = $this->createVP();

        $this
            ->actingAs($departmentManager)
            ->get(route('castle.rates.create'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.rate.create');
    }

    /** @test */
    public function it_should_store_a_new_rate()
    {
        [$departmentManager, $department] = $this->createVP();

        $data = [
            'name'          => 'rate',
            'time'          => 25,
            'rate'          => 2.5,
            'department_id' => $department->id,
            'role'          => Role::SALES_REP,
        ];

        $this->actingAs($departmentManager);

        $this->post(route('castle.rates.store'), $data)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('castle.rates.index'));
    }

    /** @test */
    public function it_shouldnt_store_a_repeated_rate()
    {
        [$departmentManager, $department] = $this->createVP();

        $data = [
            'name'          => 'rate',
            'time'          => 25,
            'rate'          => 2.5,
            'department_id' => $department->id,
            'role'          => Role::SALES_REP,
        ];

        Rates::factory()->create($data);

        $this->actingAs($departmentManager)
            ->post(route('castle.rates.store'), $data);

        $this->assertDatabaseCount('rates', 1);
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_rate()
    {
        [$departmentManager] = $this->createVP();

        $data = [
            'name'          => '',
            'time'          => '',
            'rate'          => '',
            'department_id' => '',
            'role'          => Role::SALES_REP,
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
