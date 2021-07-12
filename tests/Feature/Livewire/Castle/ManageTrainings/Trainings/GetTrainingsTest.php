<?php

namespace Tests\Feature\Livewire\Castle\ManageTrainings\Trainings;

use App\Enum\Role;
use App\Http\Livewire\Castle\ManageTrainings\Trainings;
use App\Models\Department;
use App\Models\SectionFile;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Livewire\Livewire;
use Tests\TestCase;

class GetTrainingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_change_tabs()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $department = Department::factory()->create();
        TrainingPageSection::factory()->create(['department_id' => $department->id]);

        $this->actingAs($john);

        Livewire::test(Trainings::class, ['department' => $department])
            ->assertSet('filesTabSelected', true)
            ->assertSet('trainingTabSelected', false)
            ->call('changeTab', 'training')
            ->assertSet('filesTabSelected', false)
            ->assertSet('trainingTabSelected', true)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_attach_first_department_in_db_if_auth_user_is_admin_and_no_department_provided()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Collection $departments */
        $departments = Department::factory()->times(2)->create();
        TrainingPageSection::factory()->create(['department_id' => $departments->first()->id]);

        $this->actingAs($john);

        Livewire::test(Trainings::class)
            ->assertSet('department.id', $departments->first()->id)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_be_possible_change_department()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        $department01 = Department::factory()->create();
        $department02 = Department::factory()->create();
        TrainingPageSection::factory()->create(['department_id' => $department01->id]);

        $this->actingAs($john);

        Livewire::test(Trainings::class, ['department' => $department01])
            ->call('changeDepartment', $department02->id)
            ->assertRedirect(route('castle.manage-trainings.index', ['department' => $department02->id]))
            ->assertHasNoErrors();

        Livewire::test(Trainings::class, ['department' => $department01])
            ->call('changeDepartment', 99)
            ->assertHasNoErrors();
    }

    /** @test */
    public function can_see_actions_must_return_true_if_admin_or_department_manager_is_authenticated()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);
        $ann  = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        TrainingPageSection::factory()->create(['department_id' => $department->id]);

        $this->actingAs($john);
        Livewire::test(Trainings::class, ['department' => $department])
            ->assertSet('canSeeActions', true)
            ->assertHasNoErrors();

        $this->actingAs($ann);
        Livewire::test(Trainings::class, ['department' => $department])
            ->assertSet('canSeeActions', true)
            ->assertHasNoErrors();
    }

    /** @test */
    public function can_see_actions_must_return_false_if_region_manager_is_authenticated_and_the_section_is_a_department_section(
    )
    {
        $john = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        TrainingPageSection::factory()->create([
            'department_id'     => $department->id,
            'department_folder' => true
        ]);

        $this->actingAs($john);
        Livewire::test(Trainings::class, ['department' => $department])
            ->assertSet('canSeeActions', false)
            ->assertHasNoErrors();
    }

    /** @test */
    public function can_see_actions_must_return_true_if_region_manager_is_authenticated_and_the_section_is_not_a_department_section(
    )
    {
        $john = User::factory()->create(['role' => Role::REGION_MANAGER]);

        /** @var Department $department */
        $department = Department::factory()->create();
        TrainingPageSection::factory()->create([
            'department_id'     => $department->id,
            'department_folder' => false
        ]);

        $this->actingAs($john);
        Livewire::test(Trainings::class, ['department' => $department])
            ->assertSet('canSeeActions', true)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_be_possible_search_sections()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Department $department */
        $department = Department::factory()->create();
        $root       = TrainingPageSection::factory()->create(['department_id' => $department->id]);
        TrainingPageSection::factory()->create([
            'title'         => 'Section 01',
            'department_id' => $department->id,
            'parent_id'     => $root->id,
        ]);
        TrainingPageSection::factory()->create([
            'title'         => 'Section 02',
            'department_id' => $department->id,
            'parent_id'     => $root->id,
        ]);

        $this->actingAs($john);
        Livewire::test(Trainings::class, ['department' => $department])
            ->assertSee('Section 01')
            ->assertSee('Section 02')
            ->set('search', '02')
            ->assertDontSee('Section 01')
            ->assertSee('Section 02')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_be_possible_get_fresh_files()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Department $department */
        $department = Department::factory()->create();
        /** @var TrainingPageSection $section */
        $section = TrainingPageSection::factory()->create(['department_id' => $department->id]);
        $files   = [];

        $files[] = SectionFile::factory()->create([
            'training_page_section_id' => $section->id,
            'training_type'            => 'files'
        ]);

        $this->actingAs($john);
        $component = Livewire::test(Trainings::class, ['department' => $department])
            ->assertSet('groupedFiles.files.0.name', $files[0]['name'])
            ->assertHasNoErrors();


        $files[] = SectionFile::factory()->create([
            'training_page_section_id' => $section->id,
            'training_type'            => 'files'
        ]);

        $component->call('getFreshFiles')
            ->assertSet('groupedFiles.files.0.name', $files[0]['name'])
            ->assertSet('groupedFiles.files.1.name', $files[1]['name']);
    }

    /** @test */
    public function it_should_be_possible_call_sort_by()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Department $department */
        $department = Department::factory()->create();
        TrainingPageSection::factory()->create(['department_id' => $department->id]);

        $this->actingAs($john);

        Livewire::test(Trainings::class, ['department' => $department])
            ->call('sortBy')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_get_parent_paths()
    {
        $john = User::factory()->create(['role' => Role::ADMIN]);

        /** @var Department $department */
        $department   = Department::factory()->create();
        $root         = TrainingPageSection::factory()->create(['department_id' => $department->id]);
        $childSection = TrainingPageSection::factory()->create([
            'department_id' => $department->id,
            'parent_id'     => $root->id
        ]);
        $this->actingAs($john);

        Livewire::test(Trainings::class, [
            'department' => $department,
            'section'    => $childSection
        ])
            ->assertSet('path.0.title', $root->title)
            ->assertSet('path.1.title', $childSection->title)
            ->assertHasNoErrors();
    }
}
