<?php

namespace Tests\Feature\Livewire\Castle\Region;

use App\Http\Livewire\Castle\Regions;
use App\Models\Department;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DestroyRegionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_soft_delete_a_region()
    {
        $john   = User::factory()->create(['role' => 'Region Manager']);
        $region = Region::factory()->create([
            'region_manager_id' => $john->id,
        ]);

        $this->actingAs($john);

        Livewire::test(Regions::class)
            ->call('setDeletingRegion', $region)
            ->assertSet('deletingRegion', $region)
            ->call('destroy', ['deletingName' => $region->name]);

        $this->assertSoftDeleted($region);
    }

    /** @test */
    public function it_should_soft_delete_the_training_page_sections_that_belongs_to_the_region()
    {
        $john = User::factory()->create(['role' => 'Region Manager']);

        $department = Department::factory()->create();

        $region = Region::factory()->create([
            'region_manager_id' => $john->id,
            'department_id'     => $department->id,
        ]);

        $anotherSections = TrainingPageSection::factory()->times(5)->create([
            'parent_id'         => null,
            'region_id'         => $region->id,
            'department_folder' => true,
        ]);

        $root = TrainingPageSection::factory()->create([
            'parent_id'         => null,
            'department_id'     => $department->id,
            'region_id'         => $region->id,
            'department_folder' => true,
        ]);

        $regionSections  = TrainingPageSection::factory()->times(5)->create([
            'parent_id'         => $root->id,
            'department_id'     => $department->id,
            'region_id'         => $region->id,
            'department_folder' => false,
        ]);

        $this->actingAs($john);

        Livewire::test(Regions::class)
            ->call('setDeletingRegion', $region)
            ->assertSet('deletingRegion', $region)
            ->call('destroy', ['deletingName' => $region->name]);

        $this->assertSoftDeleted($region);

        $anotherSections->each(fn (TrainingPageSection $section) => $this->assertNull($section->deleted_at));
        $regionSections->each(fn (TrainingPageSection $section) => $this->assertSoftDeleted($section));
    }
}
