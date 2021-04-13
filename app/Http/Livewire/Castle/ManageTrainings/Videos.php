<?php

namespace App\Http\Livewire\Castle\ManageTrainings;

use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Livewire\Component;

class Videos extends Component
{
    use AuthorizesRequests;

    /** @var Collection TrainingPageContent[] */
    public Collection $contents;

    public TrainingPageSection $currentSection;

    public string $updateRoute;

    public ?TrainingPageContent $selectedContent;

    public bool $showVideoModal = false;

    public bool $showEditVideoModal = false;

    public bool $showActions = true;

    public function mount()
    {
        $this->selectedContent = new TrainingPageContent();
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
        $this->authorize('delete', $content);

        $this->selectedContent = $content;
        $this->dispatchBrowserEvent('confirm', ['content' => $content]);
    }

    public function destroyVideo()
    {
        $this->authorize('delete', $this->selectedContent);

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
