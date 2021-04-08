<?php

namespace App\Http\Livewire\Castle\ManageTrainings;

use App\Models\TrainingPageContent;
use Illuminate\Support\Collection;
use Livewire\Component;

class Videos extends Component
{
    /** @var Collection[TrainingPageContent] */
    public Collection $contents;

    public function mount($contents)
    {
        $this->contents = $contents;
    }

    public function render()
    {
        return view('livewire.castle.manage-trainings.videos');
    }
}
