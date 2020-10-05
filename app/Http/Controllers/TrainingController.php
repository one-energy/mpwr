<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Illuminate\Support\Facades\Validator;

class TrainingController extends Controller
{
    public function index(TrainingPageSection $section = null)
    {
        $content = [];
        $index   = 0;
        $videoId = 0;
        $actualSection = 0;
        $path          = [];

        if(user()->department_id){
            $videoId       = null;
            $content       = $this->getContent($section);
            $actualSection = $section ?? TrainingPageSection::whereTitle("Training Page");
            $actualSection->whereDepartmentId(user()->department_id)->first();
            $index = 0;
            if ($content) {
                $videoId = explode('/', $content->video_url);
                $index   = count($videoId);
            }
            $path = $this->getPath($actualSection);
        }

        return view('training.index', [
            'sections'      => user()->department_id ? $this->getParentSections($section) : [],
            'content'       => $content,
            'videoId'       => $videoId[$index - 1] ?? null,
            'actualSection' => $actualSection,
            'path'          => $path,
            // 'departmentId'  => $this->departmentId,
        ]);
    }

    public function manageTrainings(Department $department, TrainingPageSection $section = null)
    {
        $videoId       = null;
        $content       = $this->getContent($section);
        $actualSection = $section ?? TrainingPageSection::whereDepartmentId($department->id ?? 0)->first();
        $departments   = Department::all();
        $index         = 0; 

        if ($content) {
            $videoId = explode('/', $content->video_url);
            $index   = count($videoId);
        }

        $path = $this->getPath($actualSection);

        return view('castle.manage-trainings.index', [
            'sections'      => $this->getParentSections($actualSection),
            'content'       => $content,
            'videoId'       => $videoId[$index - 1] ?? null,
            'actualSection' => $actualSection,
            'path'          => $path,
            'departmentId'  => $department->id,
            'departments'   => $departments
        ]);
    }

    public function deleteSection(TrainingPageSection $section)
    {
        $childsSections = TrainingPageSection::query()->whereParentId($section->id)->get();
        
        foreach ($childsSections as $childSection) {
            $childSection->parent_id = $section->parent_id;
            $childSection->save();
        }
        
        $section->delete();

        return redirect(route('castle.manage-trainings.index', [
            'department' => $section->department_id,
            'section'    => $section->parent_id
        ]));
    }

    public function storeSection(Department $department, TrainingPageSection $section)
    {
        $validated = $this->validate(
            request(),
            [
                'title'     => 'required|string|min:5|max:255',
            ],
        );        
        $trainingPageSection                = new TrainingPageSection();
        $trainingPageSection->title         = $validated['title'];
        $trainingPageSection->parent_id     = $section->id;
        $trainingPageSection->department_id = $department->id;

        $trainingPageSection->save();

        alert()
            ->withTitle(__('Section created!'))
            ->send();

        return redirect(route('castle.manage-trainings.index', [
            'department' => $department->id,
            'section'    => $section->id
        ]));
    }

    public function updateSection(TrainingPageSection $section)
    {
        $validated = $this->validate(
            request(),
            [
                'title'     => 'required|string|min:5|max:255',
            ],
        );    
        
        $trainingPageContent        = TrainingPageSection::query()->whereId($section->id)->first();    
        $trainingPageContent->title = $validated['title'];
        $trainingPageContent->update();

        alert()
            ->withTitle(__('Section saved!'))
            ->send();

        return redirect(route('castle.manage-trainings.index', [
            'department' => $section->department_id,
            'section'    => $section->parent_id
        ]));
    }

    public function storeContent(TrainingPageSection $section)
    {
        $validated = Validator::make(request()->all(), 
            [
                'content_title'   => 'required|string|min:5|max:255',
                'video_url'       => 'required|string|min:5|max:255',
                'description'     => 'required|string',
            ],
        )->validate();    
        
        $trainingPageContent                             = new TrainingPageContent();
        $trainingPageContent->title                      = $validated['content_title'];
        $trainingPageContent->description                = $validated['description'];
        $trainingPageContent->video_url                  = $validated['video_url'];
        $trainingPageContent->training_page_section_id   = $section->id;

        $trainingPageContent->save();

        alert()
            ->withTitle(__('Content created!'))
            ->send();

        return redirect(route('castle.manage-trainings.index', [
            'department' => $section->department_id,
            'section'    => $section->id
        ]));
    }

    public function updateContent(TrainingPageContent $content)
    {
        dd($content->section);
        $validated = $this->validate(
            request(),
            [
                'content_title'           => 'required|string|min:5|max:255',
                'video_url'               => 'required|string|min:5|max:255',
                'description'             => 'required|string',
            ],
        );    

        $trainingPageContent              = TrainingPageContent::query()->whereId($content->id)->first();
        $trainingPageContent->title       = $validated['content_title'];
        $trainingPageContent->video_url   = $validated['video_url'];
        $trainingPageContent->description = $validated['description'];

        $trainingPageContent->update();

        alert()
            ->withTitle(__('Content saved!'))
            ->send();
        return redirect(route('castle.manage-trainings.index', [
            'department' => $content->section->department_id,
            'section'    => $content->training_page_section_id
        ]));
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

    public function changeDepartment()
    {
        return redirect(route('castle.manage-trainings.index',  ['department' => request()->all()['department']] ));
    }

    public function getContent($section)
    {
        return TrainingPageContent::whereTrainingPageSectionId($section->id ?? 1)->first();
    }

    public function getParentSections($section)
    {
        return TrainingPageSection::whereParentId($section->id ?? 1)->get();
    }
}
