<?php

namespace Tests\Feature\Castle\Office;

use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\Role\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreOfficeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_store_office()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $data = $this->makeData();

        $this->assertDatabaseCount('offices', 0);
        $this->assertDatabaseCount('user_managed_offices', 0);

        $this
            ->actingAs($john)
            ->post(route('castle.offices.store', $data))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('offices', 1);
        $this->assertDatabaseCount('user_managed_offices', 2);

        /** @var Office $createdOffice */
        $createdOffice = Office::where('name', $data['name'])->first();

        $this->assertDatabaseHas('offices', [
            'name'      => $createdOffice->name,
            'region_id' => $createdOffice->region_id,
        ]);

        $this->assertSame($data['office_manager_ids'][0], $createdOffice->managers[0]->id);
        $this->assertSame($data['office_manager_ids'][1], $createdOffice->managers[1]->id);
    }

    /** @test */
    public function it_should_require_name()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this
            ->actingAs($john)
            ->post(
                route('castle.offices.store', $this->makeData(['name' => null]))
            )
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_should_require_region_id()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this
            ->actingAs($john)
            ->post(
                route('castle.offices.store', $this->makeData(['region_id' => null]))
            )
            ->assertSessionHasErrors('region_id');
    }

    /** @test */
    public function it_should_require_name_above_3_characters()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this
            ->actingAs($john)
            ->post(
                route('castle.offices.store', $this->makeData(['name' => Str::random(2)]))
            )
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_should_require_name_below_255_characters()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this
            ->actingAs($john)
            ->post(
                route('castle.offices.store', $this->makeData(['name' => Str::random(256)]))
            )
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_should_require_a_valid_region_id()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this
            ->actingAs($john)
            ->post(
                route('castle.offices.store', $this->makeData(['region_id' => Str::random(3)]))
            )
            ->assertSessionHasErrors('region_id');
    }

    /** @test */
    public function it_should_require_valid_office_manager_ids()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $data = $this->makeData(['office_manager_ids' => [Str::random(3), Str::random(3)]]);

        $this
            ->actingAs($john)
            ->post(route('castle.offices.store', $data))
            ->assertSessionHasErrors('office_manager_ids.0')
            ->assertSessionHasErrors('office_manager_ids.1');
    }

    /** @test */
    public function it_should_prevent_office_manager_ids_that_arent_from_users_that_have_office_manager_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $data = $this->makeData([
            'office_manager_ids' => [
                User::factory()->create(['role' => Role::DEPARTMENT_MANAGER])->id,
                User::factory()->create(['role' => Role::SETTER])->id,
            ],
        ]);

        $this
            ->actingAs($john)
            ->post(route('castle.offices.store', $data))
            ->assertSessionHasErrors('office_manager_ids.0')
            ->assertSessionHasErrors('office_manager_ids.1');
    }

    private function makeData(array $attributes = []): array
    {
        return array_merge([
            'name'               => Str::random(),
            'region_id'          => Region::factory()->create()->id,
            'office_manager_ids' => User::factory()
                ->times(2)
                ->create(['role' => Role::OFFICE_MANAGER])
                ->pluck('id')
                ->toArray(),
        ], $attributes);
    }
}
