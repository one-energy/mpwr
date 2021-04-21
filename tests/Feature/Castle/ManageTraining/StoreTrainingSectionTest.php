<?php

namespace Tests\Feature\Castle\ManageTraining;

use App\Models\Department;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreTrainingSectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_store_a_training_page_section()
    {
        $john    = User::factory()->create(['role' => 'Admin']);
        $section = TrainingPageSection::factory()->create();

        $title = Str::random(30);

        $this->assertDatabaseCount($section->getTable(), 1);

        $this
            ->actingAs($john)
            ->post(route('castle.manage-trainings.storeSection', ['section' => $section->id]), [
                'title' => $title,
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount($section->getTable(), 2);
        $this->assertDatabaseHas($section->getTable(), [
            'title' => $title,
        ]);
    }

    /** @test */
    public function it_should_allow_a_region_manager_create_section_inside_the_region_folder()
    {
        $john = User::factory()->create(['role' => 'Region Manager']);

        $department = Department::factory()->create([
            'department_manager_id' => User::factory()->create(['role' => 'Department Manager'])->id,
        ]);

        $region = Region::factory()->create(['region_manager_id' => $john->id]);

        $rootSection   = TrainingPageSection::factory()->create([
            'department_id' => $department->id,
            'region_id'     => $region->id,
        ]);
        $regionSection = TrainingPageSection::factory()->create([
            'parent_id'         => $rootSection->id,
            'department_id'     => $department->id,
            'region_id'         => $region->id,
            'department_folder' => false,
        ]);

        $title = Str::random(30);

        $this->assertDatabaseCount($regionSection->getTable(), 2);

        $this
            ->actingAs($john)
            ->post(route('castle.manage-trainings.storeSection', ['section' => $regionSection->id]), [
                'title' => $title,
            ])
            ->assertSessionHasNoErrors();

        /** @var TrainingPageSection $createdSection */
        $createdSection = TrainingPageSection::query()->where('title', $title)->first();

        $this->assertDatabaseCount($regionSection->getTable(), 3);
        $this->assertSame($title, $createdSection->title);
        $this->assertSame($region->id, $createdSection->region_id);
        $this->assertSame($department->id, $createdSection->department_id);
        $this->assertNotNull($createdSection->parent_id);
        $this->assertFalse($createdSection->department_folder);
    }

    /** @test */
    public function it_should_prevent_a_region_manager_create_a_section_in_department_folders()
    {
        $john    = User::factory()->create(['role' => 'Region Manager']);
        $section = TrainingPageSection::factory()->create();

        $title = Str::random(30);

        $this->assertDatabaseCount($section->getTable(), 1);

        $this
            ->actingAs($john)
            ->post(route('castle.manage-trainings.storeSection', ['section' => $section->id]), [
                'title' => $title,
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('alert');

        $this->assertDatabaseCount($section->getTable(), 1);
        $this->assertDatabaseMissing($section->getTable(), [
            'title' => $title,
        ]);
    }
}