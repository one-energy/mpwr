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
}
