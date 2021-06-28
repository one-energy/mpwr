<?php

namespace Tests\Feature\Castle\ManageTraining;

use App\Enum\Role;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\SectionFile;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class DestroyTrainingSectionTest extends TestCase
{
    use RefreshDatabase;

    private string $deleteRoute = 'castle.manage-trainings.deleteSection';

    /** @test */
    public function it_should_soft_delete_a_training_section()
    {
        $john         = User::factory()->create(['role' => Role::ADMIN]);
        $department   = Department::factory()->create(['department_manager_id' => $john->id]);
        $rootSection  = $this->createSection($department);
        $childSection = $this->createSection($department, ['parent_id' => $rootSection->id]);

        $john->update(['department_id' => $department->id]);

        $this->assertNull($childSection->deleted_at);

        $this->actingAs($john)
            ->delete(route($this->deleteRoute, ['section' => $childSection->id]))
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertSoftDeleted($childSection->fresh());
    }

    /** @test */
    public function it_should_allow_a_region_manager_destroy_section_that_belongs_to_their_region()
    {
        [$regionManager, $department, $rootSection] = $this->createTeam();

        $regionSection = $this->createSection($department, [
            'parent_id'         => $rootSection->id,
            'region_id'         => $regionManager->managedRegions()->first()->id,
            'department_id'     => $department->id,
            'department_folder' => false
        ]);

        $this->actingAs($regionManager)
            ->delete(route($this->deleteRoute, ['section' => $regionSection->id]))
            ->assertStatus(Response::HTTP_FOUND);

        $this->assertSoftDeleted($regionSection->fresh());
    }

    /** @test */
    public function it_should_require_a_valid_section_id()
    {
        $john = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        $this->actingAs($john)
            ->delete(route($this->deleteRoute, ['section' => Str::random(5)]))
            ->assertNotFound();
    }

    /** @test */
    public function it_should_forbidden_destroy_root_section()
    {
        $john        = User::factory()->create(['role' => Role::ADMIN]);
        $ann         = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $department  = Department::factory()->create(['department_manager_id' => $john->id]);
        $rootSection = $this->createSection($department);

        $this->actingAs($ann)
            ->delete(route($this->deleteRoute, ['section' => $rootSection->id]))
            ->assertForbidden();

        $this->assertNull($rootSection->deleted_at);
        $this->assertDatabaseHas($rootSection->getTable(), ['id' => $rootSection->id]);
    }

    /** @test */
    public function it_should_forbidden_a_department_manager_destroy_a_section_from_another_department()
    {
        $john         = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $ann          = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $department   = Department::factory()->create(['department_manager_id' => $john->id]);
        $rootSection  = $this->createSection($department);
        $childSection = $this->createSection($department, ['parent_id' => $rootSection]);

        $this->actingAs($ann)
            ->delete(route($this->deleteRoute, ['section' => $childSection->id]))
            ->assertForbidden();

        $this->assertNull($childSection->deleted_at);
        $this->assertDatabaseHas($childSection->getTable(), ['id' => $childSection->id]);
    }

    /** @test */
    public function it_should_forbidden_a_region_manager_destroy_a_section_that_belongs_to_the_department()
    {
        [$regionManager, $department, $rootSection] = $this->createTeam();
        $childSection = $this->createSection($department, ['parent_id' => $rootSection->id]);

        $this->actingAs($regionManager)
            ->delete(route($this->deleteRoute, ['section' => $childSection->id]))
            ->assertForbidden();

        $this->assertNull($childSection->deleted_at);
        $this->assertDatabaseHas($childSection->getTable(), ['id' => $childSection->id]);
    }

    /** @test */
    public function it_should_forbidden_a_region_manager_destroy_a_section_that_belongs_to_another_region()
    {
        [$regionManager01, $department01, $rootSection01] = $this->createTeam();
        [$regionManager02] = $this->createTeam();

        $childSection = $this->createSection($department01, [
            'parent_id'         => $rootSection01,
            'region_id'         => $regionManager01->managedRegions()->first()->id,
            'department_folder' => false
        ]);

        $this->actingAs($regionManager02)
            ->delete(route($this->deleteRoute, ['section' => $childSection->id]))
            ->assertForbidden();

        $this->assertNull($childSection->deleted_at);
        $this->assertDatabaseHas($childSection->getTable(), ['id' => $childSection->id]);
    }

    /** @test */
    public function it_should_move_children_sections_and_content_to_parent()
    {
        $john         = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $department   = Department::factory()->create(['department_manager_id' => $john->id]);
        $rootSection  = $this->createSection($department);
        $childSection = $this->createSection($department, ['parent_id' => $rootSection]);

        $john->update(['department_id' => $department->id]);

        /** @var TrainingPageContent $childContent */
        $childContent = TrainingPageContent::factory(['training_page_section_id' => $childSection->id])->create();

        /** @var SectionFile $childSectionFile */
        $childSectionFile = SectionFile::factory()->create(['training_page_section_id' => $childSection->id]);

        $this->assertCount(0, $rootSection->contents);
        $this->assertCount(0, $rootSection->files);
        $this->assertCount(1, $childSection->contents);
        $this->assertCount(1, $childSection->files);
        $this->assertEquals($childSection->id, $childContent->training_page_section_id);
        $this->assertEquals($childSection->id, $childSectionFile->training_page_section_id);

        $this->assertNull($childSection->deleted_at);

        $this->actingAs($john)
            ->delete(route($this->deleteRoute, ['section' => $childSection->id]))
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect(route('castle.manage-trainings.index', [
                'department' => $childSection->department_id,
                'section'    => $childSection->parent_id,
            ]));

        $childSection->refresh();
        $rootSection->refresh();
        $childSectionFile->refresh();
        $childContent->refresh();

        $this->assertSoftDeleted($childSection);

        $this->assertCount(1, $rootSection->contents);
        $this->assertCount(1, $rootSection->files);
        $this->assertCount(0, $childSection->contents);
        $this->assertCount(0, $childSection->files);
        $this->assertEquals($rootSection->id, $childContent->training_page_section_id);
        $this->assertEquals($rootSection->id, $childSectionFile->training_page_section_id);
    }

    private function createSection(Department $department, array $attributes = []): TrainingPageSection
    {
        $defaultAttrs = array_merge(['department_id' => $department->id], $attributes);

        return TrainingPageSection::factory()->create($defaultAttrs);
    }

    private function createTeam(): array
    {
        $departmentManager = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $regionManager     = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $officeManager     = User::factory()->create(['role' => Role::OFFICE_MANAGER]);

        $department = Department::factory()->create(['department_manager_id' => $departmentManager->id]);
        $region     = Region::factory()->create([
            'department_id'     => $department->id,
            'region_manager_id' => $regionManager->id,
        ]);
        $office     = Office::factory()->create([
            'region_id'         => $region->id,
            'office_manager_id' => $officeManager->id,
        ]);

        $regionManager->update(['office_id' => $office->id, 'department_id' => $department->id]);

        $rootSection = $this->createSection($department);

        return [$regionManager, $department, $rootSection];
    }
}
