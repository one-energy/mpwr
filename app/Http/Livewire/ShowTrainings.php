<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Livewire\Component;

class ShowTrainings extends Component
{
    public $content       = [];
    public $sections      = [];
    public $videoId       = [];
    public $actualSection = [];
    public $path          = [];

    public function render()
    {
        return view('livewire.show-trainings');
    }

    public function mount(Department $department, TrainingPageSection $section = null)
    { 
        $index         = 0;

        if ($department->id) {
            $actualSection = $section ?? TrainingPageSection::whereDepartmentId($department->id)->first();
            $actualSection->whereDepartmentId(user()->department_id)->first();
            $index         = 0;
        
            $this->content       = $this->getContent($actualSection);
            $this->sections = $department->id ? $this->getParentSections($actualSection) : [];
            if ($this->content) {
                $this->videoId = explode('/', $this->content->video_url);
                $index   = count($this->videoId);
            }
            
                // dd(strpos(strtoupper($trainings[0]->title), strtoupper($search)));

            
            $this->path = $this->getPath($actualSection);
        }
        $this->videoId = $this->videoId[$index - 1] ?? null;

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
