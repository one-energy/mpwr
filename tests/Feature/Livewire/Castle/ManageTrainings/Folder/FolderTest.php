<?php

namespace Tests\Feature\Livewire\Castle\ManageTrainings\Folder;

use App\Enum\Role;
use App\Http\Livewire\Castle\ManageTrainings\Folders;
use App\Models\Department;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FolderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_be_possible_see_the_name_of_the_provided_sections()
    {
        $john             = User::factory()->create(['role' => Role::ADMIN]);
        $rootSection      = TrainingPageSection::factory()->create();
        $childrenSections = TrainingPageSection::factory()->times(2)->create(['parent_id' => $rootSection->id]);

        $this->actingAs($john);

        Livewire::test(Folders::class, [
            'currentSection' => $rootSection,
            'sections'       => $childrenSections
        ])
            ->assertSee($childrenSections->first()->title)
            ->assertSee($childrenSections->last()->title)
            ->assertHasNoErrors();
    }

    /** @test */
    public function a_department_managed_should_be_able_to_see_folder_actions()
    {
        $john             = User::factory()->create(['role' => Role::DEPARTMENT_MANAGER]);
        $department       = Department::factory()->create(['department_manager_id' => $john->id]);
        $rootSection      = TrainingPageSection::factory()->create(['department_id' => $department->id]);
        $childrenSections = TrainingPageSection::factory()->times(2)->create(['parent_id' => $rootSection->id]);

        $this->actingAs($john);

        Livewire::test(Folders::class, [
            'currentSection' => $rootSection,
            'sections'       => $childrenSections
        ])
            ->assertSeeHtml($this->pencilIcon())
            ->assertSeeHtml($this->trashIcon())
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_set_editing_section_variable_when_call_set_editing_section()
    {
        $john             = User::factory()->create(['role' => Role::ADMIN]);
        $rootSection      = TrainingPageSection::factory()->create();
        $childrenSections = TrainingPageSection::factory()->times(2)->create(['parent_id' => $rootSection->id]);

        $this->actingAs($john);

        Livewire::test(Folders::class, [
            'currentSection' => $rootSection,
            'sections'       => $childrenSections
        ])
            ->assertSet('editingSection', null)
            ->call('setEditingSection', $childrenSections->first())
            ->assertSet('editingSection', $childrenSections->first())
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_set_editing_section_variable_to_null_when_call_close_editing_section()
    {
        $john             = User::factory()->create(['role' => Role::ADMIN]);
        $rootSection      = TrainingPageSection::factory()->create();
        $childrenSections = TrainingPageSection::factory()->times(2)->create(['parent_id' => $rootSection->id]);

        $this->actingAs($john);

        Livewire::test(Folders::class, [
            'currentSection' => $rootSection,
            'sections'       => $childrenSections
        ])
            ->set('editingSection', $childrenSections->first())
            ->call('closeEditingSection')
            ->assertSet('editingSection', null)
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_be_possible_edit_a_section_name()
    {
        $john             = User::factory()->create(['role' => Role::ADMIN]);
        $rootSection      = TrainingPageSection::factory()->create();
        $childrenSections = TrainingPageSection::factory()->times(2)->create(['parent_id' => $rootSection->id]);

        $this->actingAs($john);

        $editingSection        = $childrenSections->first();
        $editingSection->title = 'HueHue';

        Livewire::test(Folders::class, [
            'currentSection' => $rootSection,
            'sections'       => $childrenSections
        ])
            ->set('editingSection', $editingSection)
            ->call('saveSectionName', $editingSection, 0)
            ->assertSet('sections.0.title', 'HueHue')
            ->assertSet('editingSection', null)
            ->assertDispatchedBrowserEvent('show-alert')
            ->assertHasNoErrors();
    }

    /** @test */
    public function it_should_abort_if_the_authenticated_user_did_not_have_permission_to_do_it()
    {
        $john             = User::factory()->create(['role' => Role::REGION_MANAGER]);
        $rootSection      = TrainingPageSection::factory()->create();
        $childrenSections = TrainingPageSection::factory()->times(2)->create(['parent_id' => $rootSection->id]);

        $this->actingAs($john);

        Livewire::test(Folders::class, [
            'currentSection' => $rootSection,
            'sections'       => $childrenSections
        ])
            ->call('onDestroy', $childrenSections->first())
            ->assertForbidden();
    }

    /** @test */
    public function it_should_dispatch_an_event_browser_when_call_on_destroy_method()
    {
        $john             = User::factory()->create(['role' => Role::ADMIN]);
        $rootSection      = TrainingPageSection::factory()->create();
        $childrenSections = TrainingPageSection::factory()->times(2)->create(['parent_id' => $rootSection->id]);

        $this->actingAs($john);

        Livewire::test(Folders::class, [
            'currentSection' => $rootSection,
            'sections'       => $childrenSections
        ])
            ->call('onDestroy', $childrenSections->first())
            ->assertDispatchedBrowserEvent('on-destroy-section', ['section' => $childrenSections->first()])
            ->assertHasNoErrors();
    }

    private function pencilIcon()
    {
        return '<path d="M9 19c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5-17v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712zm-3 4v16h-14v-16h-2v18h18v-18h-2z" />';
    }

    private function trashIcon()
    {
        return '<path d="M9 19c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm4 0c0 .552-.448 1-1 1s-1-.448-1-1v-10c0-.552.448-1 1-1s1 .448 1 1v10zm5-17v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.315c0 .901.73 2 1.631 2h5.712zm-3 4v16h-14v-16h-2v18h18v-18h-2z" />';
    }
}
