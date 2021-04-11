<?php

namespace App\Http\Livewire\Castle\ManageTrainings;

use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Illuminate\Support\Collection;
use Livewire\Component;

class Videos extends Component
{
    /** @var Collection[TrainingPageContent] */
    public Collection $contents;

    public TrainingPageSection $currentSection;

    public string $updateRoute;

    public ?TrainingPageContent $selectedContent;

    public function mount(Collection $contents, TrainingPageSection $currentSection)
    {
        $this->contents       = $contents;
        $this->currentSection = $currentSection;
    }

    public function render()
    {
        return view('livewire.castle.manage-trainings.videos');
    }

    public function onEdit(TrainingPageContent $content)
    {
        $this->updateRoute = route('castle.manage-trainings.updateContent', $content->id);
        $this->dispatchBrowserEvent('on-edit-content', ['content' => $content]);
    }

    public function onDestroy(TrainingPageContent $content)
    {
        $this->selectedContent = $content;
        $this->dispatchBrowserEvent('confirm', ['content' => $content]);
    }

    public function destroyVideo(TrainingPageContent $content)
    {
        $content->delete();

        $this->contents = $this->contents->filter(
            fn (TrainingPageContent $c) => $c->id !== $content->id
        );

        $this->selectedContent = null;

        $this->dispatchBrowserEvent('close-modal');

        alert()
            ->withTitle(__('Video has been deleted!'))
            ->livewire($this)
            ->send();
    }
}
