<?php

namespace Tests\Feature\Castle\Rate;

use App\Enum\Role;
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
        [$departmentManager, $department] = $this->createVP();

        $rate = Rates::factory()->create([
            'department_id' => $department->id,
            'role'          => Role::SALES_REP,
        ]);

        $this->actingAs($departmentManager)
            ->get(route('castle.rates.edit', ['rate' => $rate->id]))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.rate.edit');
    }

    /** @test */
    public function it_should_block_the_edit_form_for_non_top_level_roles()
    {
        [$departmentManager, $department] = $this->createVP();

        $this->actingAs(User::factory()->create(['role' => Role::SETTER]));

        $rate = Rates::factory()->create([
            'department_id' => $department->id,
            'role'          => Role::SALES_REP,
        ]);

        $this->get(route('castle.rates.edit', ['rate' => $rate->id]))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_update_an_rate()
    {
        [$departmentManager, $department] = $this->createVP();

        $rate       = Rates::factory()->create([
            'department_id' => $department->id,
            'rate'          => 2.1,
            'role'          => Role::SALES_REP,
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
