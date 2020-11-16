<?php

namespace App\Http\Livewire;

use App\Models\TrainingPageSection;
use Livewire\Component;

class SearchBar extends Component
{
    public $trainings = [];
    public $search = "";
    public $sectionId;
    public $departmentId;

    public function render()
    {
        return view('livewire.search-bar');
    }

    public function mount($sectionId, $departmentId, $search)
    {
        $this->departmentId = $departmentId;
        $this->sectionId = $sectionId;
        $this->search = $search;
    }

    public function serachTrainings($departmentId)
    {
        if($this->search == ""){
            $trainings = [];
        }else{
            $this->trainings = TrainingPageSection::query()
                ->select('training_page_sections.*')
                ->where('training_page_sections.department_id', '=', $departmentId)
                ->orWhere('training_page_sections.title', 'like', '%' . $this->search . '%')
                ->leftJoin('training_page_contents', function($join) {
                    $join->on('training_page_sections.id', '=', 'training_page_section_id')
                    ->orWhere('training_page_contents.title', 'like', '%' . $this->search . '%')
                    ->orWhere('training_page_contents.description', 'like', '%' . $this->search . '%');
                })
                ->get();
        }
    }
}
