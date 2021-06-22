<?php

namespace Tests\Feature\Castle\Office;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreOfficeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_store_office()
    {
        $this->withoutExceptionHandling();

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
    public function it_should_attach_new_regions_if_user_already_managed_regions()
    {
        $this->withoutExceptionHandling();

        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var User $mary */
        $mary = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        /** @var Collection $offices */
        $offices = Office::factory()->times(2)->create();
        $offices->each(function (Office $office) use ($mary) {
            $office->managers()->attach($mary->id);
            $mary->update(['office_id' => $office->id]);
        });

        $data = $this->makeData(['office_manager_ids' => [$mary->id]]);

        $this
            ->actingAs($john)
            ->post(route('castle.offices.store', $data))
            ->assertSessionHasNoErrors();

        /** @var Office $createdOffice */
        $createdOffice = Office::where('name', $data['name'])->first();
        $mary->refresh();

        $this->assertDatabaseCount('user_managed_offices', 3);
        $this->assertCount(3, $mary->managedOffices);
        $this->assertTrue($mary->managedOffices->contains('id', $createdOffice->id));
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

    /** @test */
    public function it_should_block_the_create_form_for_non_top_level_roles()
    {
        $setter = User::factory()->create(['role' => Role::SETTER]);
        $ann    = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($ann->id);

        $this->actingAs($setter)
            ->get(route('castle.offices.create'))
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function it_should_show_the_create_form_for_top_level_roles()
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($departmentManager->id);
        $departmentManager->update(['department_id' => $department->id]);

        $this
            ->actingAs($departmentManager)
            ->get(route('castle.offices.create'))
            ->assertStatus(Response::HTTP_OK)
            ->assertViewIs('castle.offices.create');
    }

    /** @test */
    public function it_should_store_a_new_office()
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($departmentManager->id);
        $departmentManager->update(['department_id' => $department->id]);

        /** @var Region $region */
        $region = Region::factory()->create();
        $region->managers()->attach($this->user->id);

        $officeManager = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $data = [
            'name'              => 'Office',
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ];

        $response = $this->actingAs($departmentManager)
            ->post(route('castle.offices.store'), $data)
            ->assertStatus(Response::HTTP_FOUND);

        $created = Office::where('name', $data['name'])->first();

        $response->assertRedirect(route('castle.offices.index', $created));
    }

    /** @test */
    public function it_should_require_all_fields_to_store_a_new_office()
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $department->managers()->attach($departmentManager->id);
        $departmentManager->update(['department_id' => $department->id]);

        $this->actingAs($departmentManager)
            ->post(route('castle.offices.store'), [])
            ->assertSessionHasErrors(['name', 'region_id']);
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
