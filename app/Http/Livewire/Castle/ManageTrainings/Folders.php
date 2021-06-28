<?php

namespace App\Http\Livewire\Castle\ManageTrainings;

use App\Enum\Role;
use App\Models\TrainingPageSection;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Livewire\Component;

class Folders extends Component
{
    /**  @var Collection[TrainingPageSection] */
    public Collection $sections;

    public TrainingPageSection $currentSection;

    public string $sectionDestroyRoute;

    public bool $showActions = true;

    public $editingSection;

    protected $rules = [
        'editingSection.title' => 'required|string|min:6'
    ];

    public function render()
    {
        return view('livewire.castle.manage-trainings.folders');
    }

    public function canSeeActions(TrainingPageSection $section)
    {
        return $this->showActions &&
            $section->isDepartmentSection() &&
            user()->hasAnyRole([Role::ADMIN, Role::OWNER, Role::DEPARTMENT_MANAGER]);
    }

    public function onDestroy(TrainingPageSection $section)
    {
        abort_if(!$this->canSeeActions($section), Response::HTTP_FORBIDDEN);

        $this->sectionDestroyRoute = route('castle.manage-trainings.deleteSection', $section->id);
        $this->dispatchBrowserEvent('on-destroy-section', ['section' => $section]);
    }

    public function setEditingSection(TrainingPageSection $section)
    {
        $this->resetValidation('editingSection.title');
        $this->editingSection = $section;
    }

    public function closeEditingSection()
    {
        $this->editingSection = null;
        $this->resetValidation('editingSection.title');
    }

    public function saveSectionName(TrainingPageSection $section, int $sectionsIndex)
    {
        $this->validate();

        $section->title = $this->editingSection->title;
        $section->save();

        $this->sections[$sectionsIndex]->title = $section->title;
        $this->editingSection = null;

        alert()
            ->livewire($this)
            ->withTitle("Success")
            ->withDescription("Your folder title was changed to " . $section->title)
            ->send();
    }
}
