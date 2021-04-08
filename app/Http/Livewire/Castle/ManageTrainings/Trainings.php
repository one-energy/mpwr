<?php

namespace App\Http\Livewire\Castle\ManageTrainings;

use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Traits\Livewire\FullTable;
use Livewire\Component;

class Trainings extends Component
{
    use FullTable;

    public $contents      = [];

    public $sections      = [];

    public $videoId       = [];

    public $actualSection = [];

    public $path          = [];

    public $departments   = [];

    public $departmentId  = 0;

    public Department $department;

    public ?TrainingPageSection $section;

    public string $sectionDestroyRoute = '';

    protected $listeners = [
        'onDestroySection' => 'openDeleteModal',
    ];

    public function sortBy()
    {
        return 'title';
    }

    public function mount()
    {
        $this->actualSection = new TrainingPageSection();
    }

    public function render()
    {
        $index = 0;

        if (!$this->department->id && (user()->role == 'Owner' || user()->role == 'Admin')) {
            $this->department = Department::first();
        }

        if ($this->department->id) {
            $this->actualSection = $this->section ?? TrainingPageSection::whereDepartmentId($this->department->id)->first();
            $this->contents      = $this->getContents($this->actualSection);

            $this->departments   = Department::all();
            $index               = 0;

            $this->path = $this->getPath($this->actualSection);
        }
        $this->videoId      = $this->videoId[$index - 1] ?? null;
        $this->sections     = $this->department->id ? $this->getParentSections($this->actualSection) : [];
        $this->departmentId = $this->department->id ?? 0;

        return view('livewire.castle.manage-trainings.trainings');
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

    public function getContents($section)
    {
        return TrainingPageContent::whereTrainingPageSectionId($section->id)->get();
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

    public function openDeleteModal($section)
    {
        $this->sectionDestroyRoute = route('castle.manage-trainings.deleteSection', $section['id']);
        $this->dispatchBrowserEvent('on-destroy-section', ['section' => $section]);
    }
}
