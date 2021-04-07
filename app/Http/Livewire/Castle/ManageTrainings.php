<?php

namespace App\Http\Livewire\Castle;

use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class ManageTrainings extends Component
{
    use FullTable;

    public $content       = [];

    public $sections      = [];

    public $videoId       = [];

    public $actualSection = [];

    public $path          = [];

    public $departments   = [];

    public $departmentId  = 0;

    public Department $department;

    public ?TrainingPageSection $section;

    public function sortBy()
    {
        return 'title';
    }

    public function render()
    {
        $index         = 0;

        $this->actualSection = new TrainingPageSection();

        if (!$this->department->id && (user()->role == 'Owner' || user()->role == 'Admin')) {
            $this->department = Department::first();
        }

        if ($this->department->id) {
            $this->actualSection = $this->section ?? TrainingPageSection::whereDepartmentId($this->department->id)->first();
            $this->content       = $this->getContent($this->actualSection);
            $this->departments   = Department::all();
            $index               = 0;

            if ($this->content) {
                $this->videoId = explode('/', $this->content->video_url);
                $index         = count($this->videoId);
            }

            $this->path = $this->getPath($this->actualSection);
        }
        $this->videoId      = $this->videoId[$index - 1] ?? null;
        $this->sections     = $this->department->id ? $this->getParentSections($this->actualSection) : [];
        $this->departmentId = $this->department->id ?? 0;

        return view('livewire.castle.manage-trainings');
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
}
