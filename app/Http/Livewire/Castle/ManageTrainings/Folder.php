<?php

namespace App\Http\Livewire\Castle\ManageTrainings;

use App\Models\TrainingPageSection;
use Livewire\Component;

class Folder extends Component
{
    public TrainingPageSection $section;

    public function mount(TrainingPageSection $section)
    {
        $this->section = $section;
    }

    public function getTrainingIndexRouteProperty()
    {
        return route('castle.manage-trainings.index',[
            'department' => $this->section->department_id,
            'section'    => $this->section->id,
        ]);
    }

    public function render()
    {
        return view('livewire.castle.manage-trainings.folder');
    }

    public function onDestroy()
    {
        $this->emitUp('onDestroySection', $this->section);
    }
}
