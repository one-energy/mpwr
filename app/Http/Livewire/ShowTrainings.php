<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class ShowTrainings extends Component
{
    use FullTable;
    
    public $content       = [];

    public $sections      = [];

    public $videoId       = [];

    public $actualSection = [];

    public $path          = [];

    public $section       = [];

    public $department;

    public function sortBy()
    {
        return 'title';
    }

    public function render()
    {
        $index         = 0;
        if ($this->department->id) {
            $actualSection = $this->section ?? TrainingPageSection::whereDepartmentId($this->department->id)->first();
            $index         = 0;
        
            $this->content       = $this->getContent($actualSection);
            $this->sections      = $this->department->id ? $this->getParentSections($actualSection) : [];
            // dd($this->sections);
            if ($this->content) {
                $this->videoId = explode('/', $this->content->video_url);
                $index         = count($this->videoId);
            }
            
            $this->path = $this->getPath($actualSection);
            // dd($this->content->isEmpty());
        }
        $this->videoId = $this->videoId[$index - 1] ?? null;

        return view('livewire.show-trainings');
    }

    public function mount(Department $department, TrainingPageSection $section = null)
    {
        $this->department = $department;
        $this->section    = $section;
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

    public function getContent($section)
    {
        return TrainingPageContent::whereTrainingPageSectionId($section->id)->first();
    }

    public function changeDepartment()
    {
        return redirect(route('castle.manage-trainings.index',  ['department' => request()->all()['department']] ));
    }

    public function getParentSections($section)
    {
        $search         = $this->search;
        $trainingsQuery = TrainingPageSection::query()->with('content')
            ->select( 'training_page_sections.*')
            ->whereDepartmentId($this->department->id)
            ->leftJoin('training_page_contents', 'training_page_sections.id', '=', 'training_page_contents.training_page_section_id' );
        
        $trainingsQuery->when($search == "", function ($query) use ($section) {
            $query->where('training_page_sections.parent_id', $section->id ?? 1);
        });

        $trainingsQuery->when($search != "", function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->orWhere('training_page_sections.title', "like", "%" . $this->search . "%")
                    ->orWhere('training_page_contents.description', 'like', "%" . $search . "%");
            });
        });
        
        return $trainingsQuery->get();
    }
}
