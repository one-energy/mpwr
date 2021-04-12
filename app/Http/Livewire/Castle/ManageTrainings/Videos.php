<?php

namespace App\Http\Livewire\Castle\ManageTrainings;

use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Illuminate\Support\Collection;
use Livewire\Component;

class Videos extends Component
{
    /** @var Collection TrainingPageContent[] */
    public Collection $contents;

    public TrainingPageSection $currentSection;

    public string $updateRoute;

    public ?TrainingPageContent $selectedContent;

    public bool $showVideoModal = false;

    public bool $showEditVideoModal = false;

    public bool $showActions;

    public function mount(Collection $contents, TrainingPageSection $currentSection, bool $showActions = true)
    {
        $this->contents        = $contents;
        $this->currentSection  = $currentSection;
        $this->selectedContent = new TrainingPageContent();
        $this->showActions     = $showActions;
    }

    public function render()
    {
        return view('livewire.castle.manage-trainings.videos');
    }

    public function makeVideoUrl(TrainingPageContent $content)
    {
        $videoId = explode('/', $content->video_url);
        $videoId = $videoId[count($videoId) - 1] ?? null;

        return sprintf('https://www.youtube.com/embed/%s', $videoId);
    }

    public function openShowVideoModal(TrainingPageContent $content)
    {
        $this->showVideoModal  = true;
        $this->selectedContent = $content;
    }

    public function onEdit(TrainingPageContent $content)
    {
        $this->selectedContent    = $content;
        $this->showEditVideoModal = true;
        $this->updateRoute        = route('castle.manage-trainings.updateContent', $content->id);
    }

    public function onDestroy(TrainingPageContent $content)
    {
        $this->selectedContent = $content;
        $this->dispatchBrowserEvent('confirm', ['content' => $content]);
    }

    public function destroyVideo()
    {

        $this->selectedContent->delete();

        $this->contents = $this->contents->filter(
            fn(TrainingPageContent $content) => $content->id !== $this->selectedContent->id
        );

        $this->selectedContent = new TrainingPageContent();

        $this->dispatchBrowserEvent('close-modal');

        alert()
            ->withTitle(__('Video has been deleted!'))
            ->livewire($this)
            ->send();
    }
}