<?php

namespace App\Http\Livewire\Castle\ManageTrainings;

use App\Models\TrainingPageSection;
use Illuminate\Support\Collection;
use Livewire\Component;

class Folders extends Component
{
    /**  @var Collection[TrainingPageSection] */
    public Collection $sections;

    public TrainingPageSection $currentSection;

    public string $sectionDestroyRoute;

    public bool $showActions = true;

    public function render()
    {
        return view('livewire.castle.manage-trainings.folders');
    }

    public function onDestroy(TrainingPageSection $section)
    {
        $this->sectionDestroyRoute = route('castle.manage-trainings.deleteSection', $section->id);
        $this->dispatchBrowserEvent('on-destroy-section', ['section' => $section]);
    }
}
