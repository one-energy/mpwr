<?php

namespace App\Http\Livewire\Castle;

use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Livewire\Component;

class ManageTrainings extends Component
{
    public $content       = [];
    public $sections      = [];
    public $videoId       = [];
    public $actualSection = [];
    public $path          = [];
    public $departments   = [];
    public $departmentId  = 0;

    public function render()
    {
        return view('livewire.castle.manage-trainings');
    }

    public function mount(Department $department, TrainingPageSection $section = null)
    {
        // 'sections'      => $department->id ? $this->getParentSections($actualSection) : [],
        //     'content'       => $content,
        //     'videoId'       => $videoId[$index - 1] ?? null,
        //     'actualSection' => $actualSection,
        //     'path'          => $path,
        //     'departmentId'  => $department->id ?? 0,
        //     'departments'   => $departments,
        $index         = 0;

        $this->actualSection = new TrainingPageSection();

        if (!$department->id && (user()->role == "Owner" || user()->role == "Admin")) {
            $department = Department::first();
        }
        
        if ($department->id) {
            $this->actualSection = $section ?? TrainingPageSection::whereDepartmentId($department->id)->first();
            $this->content       = $this->getContent($this->actualSection);
            $this->departments   = Department::all();
            $index         = 0; 
            
            if ($this->content) {
                $this->videoId = explode('/', $this->content->video_url);
                $index   = count($this->videoId);
            }
            
            $this->path = $this->getPath($this->actualSection);
        }
        $this->videoId = $this->videoId[$index - 1] ?? null;
        $this->sections = $department->id ? $this->getParentSections($this->actualSection) : [];
        $this->departmentId = $department->id ?? 0;
        // dd($this->departments);
  
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
        return TrainingPageSection::whereParentId($section->id ?? 1)->get();
    }
}
