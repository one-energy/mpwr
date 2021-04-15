<?php

namespace Tests\Feature\Castle;

use App\Models\Department;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreRegionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['master' => true]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function it_should_store_a_new_region()
    {
        ['regionManager' => $regionManager, 'department' => $department] = $this->makeSetup();

        $data = [
            'name'              => 'Region',
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
        ];

        $this
            ->actingAs($regionManager)
            ->post(route('castle.regions.store'), $data)
            ->assertSessionHasNoErrors()
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('castle.regions.index'));

        $this->assertDatabaseHas('regions', [
            'name'              => 'Region',
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
        ]);
    }

    /** @test */
    public function it_should_create_a_training_page_section_after_create_a_region()
    {
        ['regionManager' => $regionManager, 'department' => $department] = $this->makeSetup();

        $data = [
            'name'              => Str::random(),
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
        ];

        $this
            ->actingAs($regionManager)
            ->post(route('castle.regions.store'), $data);

        $this->assertDatabaseHas('regions', $data);

        /** @var Region */
        $region = Region::where('name', $data['name'])->first();

        $this->assertNull($region->trainingPageSections->first()->parent_id);
        $this->assertFalse($region->trainingPageSections->first()->department_folder);
    }

    /** @test */
    public function it_should_create_a_training_page_section_with_the_parent_id_of_the_root_section()
    {
        ['regionManager' => $regionManager, 'department' => $department] = $this->makeSetup();

        $rootSection = TrainingPageSection::factory()->create([
            'department_id' => $department->id,
        ]);
        $childSection = TrainingPageSection::factory()->create([
            'parent_id'     => $rootSection->id,
            'department_id' => $department->id,
        ]);

        $data = [
            'name'              => Str::random(),
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
        ];

        $this
            ->actingAs($regionManager)
            ->post(route('castle.regions.store'), $data);

        $this->assertDatabaseHas('regions', $data);

        /** @var Region */
        $region = Region::where('name', $data['name'])->first();

        $this->assertEquals($rootSection->id, $region->trainingPageSections->first()->parent_id);
        $this->assertEquals($childSection->parent_id, $region->trainingPageSections->first()->parent_id);
    }

    /** @test */
    public function it_should_require_name()
    {
        [
            'regionManager'     => $regionManager,
            'departmentManager' => $departmentManager,
            'department'        => $department
        ] = $this->makeSetup();

        $data = [
            'name'              => '',
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
        ];

        $this
            ->actingAs($departmentManager)
            ->post(route('castle.regions.store'), $data)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_should_require_region_manager_id()
    {
        ['department' => $department, 'departmentManager' => $departmentManager] = $this->makeSetup();

        $data = [
            'name'          => Str::random(),
            'department_id' => $department->id,
        ];

        $this
            ->actingAs($departmentManager)
            ->post(route('castle.regions.store'), $data)
            ->assertSessionHasErrors('region_manager_id');
    }

    /** @test */
    public function it_should_require_department_id()
    {
        ['regionManager' => $regionManager, 'departmentManager' => $departmentManager] = $this->makeSetup();

        $data = [
            'name'              => Str::random(),
            'region_manager_id' => $regionManager->id,
        ];

        $this
            ->actingAs($departmentManager)
            ->post(route('castle.regions.store'), $data)
            ->assertSessionHasErrors('department_id');
    }

    /** @test */
    public function it_should_prevent_name_below_3_characters()
    {
        ['regionManager' => $regionManager, 'department' => $department] = $this->makeSetup();

        $data = [
            'name'              => Str::random(2),
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
        ];

        $this
            ->actingAs($regionManager)
            ->post(route('castle.regions.store'), $data)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_should_prevent_name_above_255_characters()
    {
        ['regionManager' => $regionManager, 'department' => $department] = $this->makeSetup();

        $data = [
            'name'              => Str::random(256),
            'region_manager_id' => $regionManager->id,
            'department_id'     => $department->id,
        ];

        $this
            ->actingAs($regionManager)
            ->post(route('castle.regions.store'), $data)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_should_require_an_existent_region_manager_id()
    {
        ['regionManager' => $regionManager, 'department' => $department] = $this->makeSetup();

        $data = [
            'name'              => Str::random(256),
            'region_manager_id' => 99,
            'department_id'     => $department->id,
        ];

        $this
            ->actingAs($regionManager)
            ->post(route('castle.regions.store'), $data)
            ->assertSessionHasErrors('region_manager_id');
    }

    /** @test */
    public function it_should_require_an_existent_department_id()
    {
        ['regionManager' => $regionManager] = $this->makeSetup();

        $data = [
            'name'              => Str::random(256),
            'region_manager_id' => $regionManager->id,
            'department_id'     => 99,
        ];

        $this
            ->actingAs($regionManager)
            ->post(route('castle.regions.store'), $data)
            ->assertSessionHasErrors('department_id');
    }

    private function makeSetup()
    {
        $regionManager     = User::factory()->create(['role' => 'Region Manager']);
        $departmentManager = User::factory()->create(['role' => 'Department Manager']);
        $department        = Department::factory()->create([
            'department_manager_id' => $departmentManager->id,
        ]);

        $regionManager->update(['department_id' => $department->id]);

        return [
            'regionManager'     => $regionManager,
            'departmentManager' => $departmentManager,
            'department'        => $department,
        ];
    }
}
