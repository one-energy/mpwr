<?php

namespace App\Http\Livewire;

use App\Enum\Role;
use App\Models\Department;
use App\Models\SectionFile;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Builder;
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

    public Collection $groupedFiles;

    protected $queryString = ['selectedTab'];

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
        if (!$this->department->id && user()->hasAnyRole([Role::ADMIN, Role::OWNER])) {
            $this->department = Department::first();
        }

        if ($this->department->id) {
            $this->actualSection = $this->section ?? TrainingPageSection::whereDepartmentId($this->department->id)->first();
            $this->actualSection->load('files');

            $this->groupedFiles = $this->getGroupedFiles($this->actualSection);

            $this->contents      = $this->getContents($this->actualSection);
            $this->sections      = $this->department->id ? $this->getParentSections($this->actualSection) : collect();
            $this->path          = $this->getPath($this->actualSection);
        }

        return view('livewire.show-trainings');
    }

    public function getGroupedFiles(TrainingPageSection $section)
    {
        $files     = $section->files->filter(fn(SectionFile $file) => $file->training_type === 'files');
        $trainings = $section->files->filter(fn(SectionFile $file) => $file->training_type === 'training');

        return collect([
            'files'    => $files,
            'training' => $trainings,
        ]);
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
        return TrainingPageSection::query()
            ->where('department_id', $this->department->id)
            ->with('contents')
            ->when(user()->hasRole('Region Manager'), function (Builder $query) {
                $query->sectionsUserManaged();
            })
            ->where('training_page_sections.parent_id', $section->id ?? 1)
            ->get();
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
