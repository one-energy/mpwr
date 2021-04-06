<?php

namespace Tests\Feature\Livewire\Castle\Region;

use App\Http\Livewire\Castle\Regions;
use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class DestroyRegionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Department $department;

    private Region $region;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin      = User::factory()->create(['role' => 'Admin']);
        $this->department = Department::factory()->create();
        $this->region     = Region::factory()->create(['department_id' => $this->department->id]);
    }

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

    /** @test */
    public function it_should_move_all_contents_from_root_section_on_destroy_a_region()
    {
        $john = User::factory()->create(['role' => 'Region Manager']);

        $department = Department::factory()->create();

        $region = Region::factory()->create([
            'region_manager_id' => $john->id,
            'department_id'     => $department->id,
        ]);

        /** @var TrainingPageSection */
        $root = TrainingPageSection::factory()->create([
            'parent_id'         => null,
            'department_id'     => $department->id,
            'region_id'         => $region->id,
            'department_folder' => true,
        ]);

        $regionSection  = TrainingPageSection::factory()->create([
            'parent_id'         => $root->id,
            'department_id'     => $department->id,
            'region_id'         => $region->id,
            'department_folder' => false,
        ]);

        /** @var TrainingPageSection */
        $regionSubSection01 = TrainingPageSection::factory()->create([
            'parent_id'         => $regionSection->id,
            'department_id'     => $department->id,
            'region_id'         => $region->id,
            'department_folder' => false,
        ]);

        /** @var TrainingPageSection */
        $regionSubSection02 = TrainingPageSection::factory()->create([
            'parent_id'         => $regionSection->id,
            'department_id'     => $department->id,
            'region_id'         => $region->id,
            'department_folder' => false,
        ]);

        /** @var TrainingPageContent */
        $content01 = $regionSubSection01->contents()->create([
            'title'                    => Str::random(),
            'description'              => Str::random(),
            'video_url'                => Str::random(),
            'training_page_section_id' => $regionSubSection01->id,
        ]);

        /** @var TrainingPageContent */
        $content02 = $regionSubSection02->contents()->create([
            'title'                    => Str::random(),
            'description'              => Str::random(),
            'video_url'                => Str::random(),
            'training_page_section_id' => $regionSubSection02->id,
        ]);

        $this->actingAs($john);

        Livewire::test(Regions::class)
            ->call('setDeletingRegion', $region)
            ->assertSet('deletingRegion', $region)
            ->call('destroy', ['deletingName' => $region->name]);

        $this
            ->assertSoftDeleted($region)
            ->assertSoftDeleted($regionSubSection01)
            ->assertSoftDeleted($regionSubSection02);

        $this->assertSame($root->id, $content01->fresh()->training_page_section_id);
        $this->assertSame($root->id, $content02->fresh()->training_page_section_id);
    }

    /** @test */
    public function it_should_soft_delete_related_offices_when_delete_a_region()
    {
        $dummyOffice = Office::factory()->create();

        [$firstOffice, $secondOffice] = Office::factory()
            ->times(2)
            ->create(['region_id' => $this->region->id]);

        $this->actingAs($this->admin);

        Livewire::test(Regions::class)
            ->set('deletingName', $this->region->name)
            ->call('setDeletingRegion', $this->region)
            ->call('destroy');

        $this
            ->assertSoftDeleted($this->region->fresh())
            ->assertSoftDeleted($firstOffice->fresh())
            ->assertSoftDeleted($secondOffice->fresh());

        $this->assertNull($dummyOffice->deleted_at);
    }

    /** @test */
    public function it_should_soft_delete_daily_numbers_when_delete_a_region()
    {
        $office = Office::factory()->create(['region_id' => $this->region->id]);

        $mary = User::factory()->create([
            'role'      => 'Setter',
            'office_id' => $office->id,
        ]);

        $dailyNumbers = DailyNumber::factory()
            ->times(2)
            ->create(['user_id' => $mary->id]);

        $this->actingAs($this->admin);

        Livewire::test(Regions::class)
            ->set('deletingName', $this->region->name)
            ->call('setDeletingRegion', $this->region)
            ->call('destroy');

        $this->assertSoftDeleted($this->region->fresh());

        $dailyNumbers->each(fn(DailyNumber $dailyNumber) => $this->assertSoftDeleted($dailyNumber));
    }
}
