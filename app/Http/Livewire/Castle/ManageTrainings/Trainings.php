<?php

namespace App\Http\Livewire\Castle\ManageTrainings;

use App\Enum\Role;
use App\Models\Department;
use App\Models\SectionFile;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use App\Traits\Livewire\FullTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Trainings extends Component
{
    use FullTable;

    public Collection $sections;

    public $path = [];

    public $departments = [];

    public $departmentId = 0;

    public Collection $contents;

    public TrainingPageSection $actualSection;

    public Department $department;

    public ?TrainingPageSection $section;

    public TrainingPageContent $video;

    public string $selectedTab = 'files';

    public bool $showAddContentModal = false;

    public Collection $groupedFiles;

    protected $listeners = [
        'contentAdded'  => '$refresh',
        'filesUploaded' => 'getFreshFiles',
    ];

    public function sortBy()
    {
        return 'title';
    }

    public function mount(Department $department)
    {
        $this->department    = $department;
        $this->video         = new TrainingPageContent();
        $this->actualSection = new TrainingPageSection();
        $this->contents      = collect();
        $this->sections      = collect();
    }

    public function getFilesTabSelectedProperty()
    {
        return $this->selectedTab === 'files';
    }

    public function getTrainingTabSelectedProperty()
    {
        return $this->selectedTab === 'training';
    }

    public function render()
    {
        if ($this->department->id === null && user()->hasAnyRole([Role::ADMIN, Role::OWNER])) {
            $this->department = Department::first();
        }

        if ($this->department->id) {
            $this->actualSection = $this->section ?? TrainingPageSection::whereDepartmentId($this->department->id)->first();
            $this->actualSection->load('files');

            $this->groupedFiles = $this->getGroupedFiles($this->actualSection);

            $this->contents    = $this->getContents($this->actualSection);
            $this->departments = Department::all();
            $this->path        = $this->getPath($this->actualSection);
        }

        $this->sections     = $this->department->id ? $this->getParentSections($this->actualSection) : collect();
        $this->departmentId = $this->department->id ?? 0;

        return view('livewire.castle.manage-trainings.trainings');
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

    public function getCanSeeActionsProperty()
    {
        if (user()->hasAnyRole([Role::ADMIN, Role::OWNER, Role::DEPARTMENT_MANAGER])) {
            return true;
        }

        if ($this->actualSection->isDepartmentSection() && user()->hasRole(Role::REGION_MANAGER)) {
            return false;
        }

        return true;
    }

    public function getPath($section)
    {
        $path                = [$section];
        $trainingPageSection = $section;
        do {
            if ($trainingPageSection->parent_id) {
                $trainingPageSection = TrainingPageSection::query()->whereId($trainingPageSection->parent_id)->first();

                $path[] = $trainingPageSection;
            }
        } while ($trainingPageSection->parent_id);

        return array_reverse($path);
    }

    public function getContents(TrainingPageSection $section): Collection
    {
        return TrainingPageContent::whereTrainingPageSectionId($section->id)->get();
    }

    public function changeDepartment($value)
    {
        $department = Department::query()->find($value);

        if ($department === null) {
            return;
        }

        return redirect(route('castle.manage-trainings.index', ['department' => $department->id]));
    }

    public function getParentSections($section)
    {
        return TrainingPageSection::query()
            ->where('department_id', $this->department->id)
            ->with('contents')
            ->when(user()->hasRole('Region Manager'), function (Builder $query) {
                $query->sectionsUserManaged();
            })
            ->when($this->search === '', function ($query) use ($section) {
                $query->where('training_page_sections.parent_id', $section->id ?? 1);
            })
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query
                        ->orWhere('training_page_sections.title', 'like', "%{$this->search}%")
                        ->orWhereHas(
                            'contents',
                             fn($query) => $query->where('description', 'like', "%{$this->search}%")
                        );
                });
            })
            ->get();
    }

    public function changeTab(string $tabName)
    {
        $this->selectedTab = $tabName;
    }

    public function rules()
    {
        return [
            'video.title'       => 'required|string|max:100',
            'video.video_url'   => 'required|string|max:100',
            'video.description' => 'required|string|string',
        ];
    }

    public function storeVideo()
    {
        $this->validate();

        DB::transaction(function () {
            $this->video->training_page_section_id = $this->actualSection->id;

            $this->video->save();
            $this->contents->push($this->video);

            alert()
                ->withTitle(__('Video has been added!'))
                ->livewire($this)
                ->send();

            $this->video = new TrainingPageContent();

            $this->showAddContentModal = false;
        });
    }

    public function getFreshFiles()
    {
        $this->actualSection       = TrainingPageSection::find($this->actualSection->id)->load('files');
        $this->showAddContentModal = false;

        $this->groupedFiles = $this->getGroupedFiles($this->actualSection);
    }
}
