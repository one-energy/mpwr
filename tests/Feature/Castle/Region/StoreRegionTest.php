<?php

namespace Tests\Feature\Castle\Region;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreRegionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user  = User::factory()->create(['master' => true]);
        $this->admin = User::factory()->create(['role' => Role::ADMIN]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_render_create_view()
    {
        $this->actingAs($this->admin)
            ->get(route('castle.regions.create'))
            ->assertViewIs('castle.regions.create')
            ->assertSuccessful()
            ->assertOk();
    }

    /** @test */
    public function it_should_store_a_new_region()
    {
        $regionManager = User::factory()->create(['role' => Role::REGION_MANAGER]);
        /** @var User $departmentManager */
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();

        $regionManager->update(['department_id' => $department->id]);
        $departmentManager->update(['department_id' => $department->id]);
        $departmentManager->managedDepartments()->attach($department->id);

        $rootSection  = TrainingPageSection::factory()->create(['department_id' => $department->id]);
        $childSection = TrainingPageSection::factory()->create([
            'parent_id'     => $rootSection->id,
            'department_id' => $department->id,
        ]);

        $data = [
            'name'               => Str::random(),
            'region_manager_ids' => [$regionManager->id],
            'department_id'      => $department->id,
        ];

        $this
            ->actingAs($regionManager)
            ->post(route('castle.regions.store'), $data);

        $this->assertDatabaseHas('regions', collect($data)->except('region_manager_ids')->toArray());

        /** @var Region */
        $region = Region::where('name', $data['name'])->first();

        $this->assertEquals($rootSection->id, $region->trainingPageSections->first()->parent_id);
        $this->assertEquals($childSection->parent_id, $region->trainingPageSections->first()->parent_id);
    }

    /** @test */
    public function it_should_store_a_region()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Department $department */
        $department = Department::factory()->create();

        $data = $this->makeData();

        $rootSection  = TrainingPageSection::factory()->create([
            'department_id' => $department->id,
        ]);
        $childSection = TrainingPageSection::factory()->create([
            'parent_id'     => $rootSection->id,
            'department_id' => $department->id,
        ]);

        $this->assertDatabaseCount('regions', 0);
        $this->assertDatabaseCount('user_managed_regions', 0);

        $this
            ->actingAs($john)
            ->post(route('castle.regions.store', $data))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('regions', 1);
        $this->assertDatabaseCount('user_managed_regions', 2);

        /** @var Region $createdRegion */
        $createdRegion = Region::where('name', $data['name'])->first();

        $this->assertDatabaseHas('regions', [
            'name'          => $createdRegion->name,
            'department_id' => $createdRegion->department_id,
        ]);

        $this->assertSame($data['region_manager_ids'][0], $createdRegion->managers[0]->id);
        $this->assertSame($data['region_manager_ids'][1], $createdRegion->managers[1]->id);
    }

    /** @test */
    public function it_should_attach_new_regions_if_user_already_managed_regions()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var User $mary */
        $mary = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var Collection $regions */
        $regions = Region::factory()->times(2)->create();
        $regions->each(function (Region $region) use ($mary) {
            $region->managers()->attach($mary->id);
            $mary->update(['department_id' => $region->department_id]);
        });

        $data = $this->makeData(['region_manager_ids' => [$mary->id]]);

        $this
            ->actingAs($john)
            ->post(route('castle.regions.store', $data))
            ->assertSessionHasNoErrors();

        /** @var Region $createdRegion */
        $createdRegion = Region::where('name', $data['name'])->first();

        $mary->refresh();

        $this->assertDatabaseCount('user_managed_regions', 3);
        $this->assertCount(3, $mary->managedRegions);
        $this->assertTrue($mary->managedRegions->contains('id', $createdRegion->id));
    }

    /** @test */
    public function it_should_require_name()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this
            ->actingAs($john)
            ->post(
                route('castle.regions.store', $this->makeData(['name' => null]))
            )
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_should_require_department_id()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this
            ->actingAs($john)
            ->post(
                route('castle.regions.store', $this->makeData(['department_id' => null]))
            )
            ->assertSessionHasErrors('department_id');
    }

    /** @test */
    public function it_should_require_name_above_3_characters()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this
            ->actingAs($john)
            ->post(
                route('castle.regions.store', $this->makeData(['name' => Str::random(2)]))
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
                route('castle.regions.store', $this->makeData(['name' => Str::random(256)]))
            )
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_should_require_a_valid_department_id()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $this
            ->actingAs($john)
            ->post(
                route('castle.regions.store', $this->makeData(['department_id' => Str::random(3)]))
            )
            ->assertSessionHasErrors('department_id');
    }

    /** @test */
    public function it_should_prevent_region_manager_ids_that_arent_from_users_that_have_region_manager_role()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $data = $this->makeData([
            'region_manager_ids' => [
                User::factory()->create(['role' => Role::DEPARTMENT_MANAGER])->id,
                User::factory()->create(['role' => Role::SETTER])->id,
            ],
        ]);

        $this
            ->actingAs($john)
            ->post(route('castle.regions.store', $data))
            ->assertSessionHasErrors('region_manager_ids.0')
            ->assertSessionHasErrors('region_manager_ids.1');
    }

    private function makeData(array $attributes = []): array
    {
        return array_merge([
            'name'               => Str::random(),
            'department_id'      => Department::factory()->create()->id,
            'region_manager_ids' => User::factory()
                ->times(2)
                ->create(['role' => Role::REGION_MANAGER])
                ->pluck('id')
                ->toArray(),
        ], $attributes);
    }
}
