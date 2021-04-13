<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Traits\Livewire\FullTable;
use Illuminate\Support\Collection;
use Livewire\Component;

class ShowTrainings extends Component
{
    use FullTable;

    public Collection $contents;

    public Collection $sections;

    public TrainingPageSection $actualSection;

    public $path = [];

    public ?TrainingPageSection $section;

    public Department $department;

    public string $selectedTab = 'files';

    public function mount()
    {
        $this->video         = new TrainingPageContent();
        $this->actualSection = new TrainingPageSection();
        $this->contents      = collect();
        $this->sections      = collect();
    }

    public function sortBy()
    {
        return 'title';
    }

    public function render()
    {
        if ($this->department->id) {
            $this->actualSection = $this->section ?? TrainingPageSection::whereDepartmentId($this->department->id)->first();

            $this->actualSection->load('files');

            $this->contents      = $this->getContents($this->actualSection);
            $this->sections      = $this->department->id ? $this->getParentSections($this->actualSection) : collect();
            $this->path          = $this->getPath($this->actualSection);
        }

        return view('livewire.show-trainings');
    }

    public function getPath($section)
    {
        $path                = [$section];
        $trainingPageSection = $section;
        do {
            if ($trainingPageSection->parent_id) {
                $trainingPageSection = TrainingPageSection::query()->whereId($trainingPageSection->parent_id)->first();
                array_push($path, $trainingPageSection);
            }
        } while ($trainingPageSection->parent_id);

        return array_reverse($path);
    }

    public function getContents(TrainingPageSection $section): Collection
    {
        return TrainingPageContent::whereTrainingPageSectionId($section->id)->get();
    }

    public function changeDepartment()
    {
        return redirect(route('castle.manage-trainings.index', ['department' => request()->all()['department']]));
    }

    public function getParentSections($section)
    {
        $search         = $this->search;
        $trainingsQuery = TrainingPageSection::query()->with('content')
            ->select('training_page_sections.*')
            ->whereDepartmentId($this->department->id)
            ->leftJoin('training_page_contents', 'training_page_sections.id', '=',
                'training_page_contents.training_page_section_id');

        $trainingsQuery->when($search == '', function ($query) use ($section) {
            $query->where('training_page_sections.parent_id', $section->id ?? 1);
        });

        $trainingsQuery->when($search != '', function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('training_page_sections.title', 'like', '%' . $this->search . '%')
                    ->orWhere('training_page_contents.description', 'like', '%' . $search . '%');
            });
        });

        return $trainingsQuery->get();
    }

    public function getFilesTabSelectedProperty()
    {
        return $this->selectedTab === 'files';
    }

    public function getTrainingTabSelectedProperty()
    {
        return $this->selectedTab === 'training';
    }

    public function changeTab(string $tabName)
    {
        if ($this->selectedTab === $tabName) {
            return;
        }

        $this->selectedTab = $tabName;
    }
}
