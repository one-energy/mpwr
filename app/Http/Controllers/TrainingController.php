<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;
use Illuminate\Support\Facades\Validator;

class TrainingController extends Controller
{
    public function index(Department $department, TrainingPageSection $section = null)
    {
        // dd($section);
        $content = [];
        $index   = 0;
        $videoId = [];
        $actualSection = 0;
        $path          = [];
        if($department->id){
               
            $actualSection = $section ?? TrainingPageSection::whereDepartmentId($department->id)->first();
            $content       = $this->getContent($actualSection);
            $actualSection->whereDepartmentId(user()->department_id)->first();
            $index         = 0;
            if ($content) {
                $videoId = explode('/', $content->video_url);
                $index   = count($videoId);
            }
            $path = $this->getPath($actualSection);
            
        }

        return view('training.index', [
            'sections'      => $department->id ? $this->getParentSections($actualSection) : [],
            'content'       => $content,
            'videoId'       => $videoId[$index - 1] ?? null,
            'actualSection' => $actualSection,
            'path'          => $path,
        ]);
    }

    public function manageTrainings(Department $department, TrainingPageSection $section = null)
    {
        $content = [];
        $index   = 0;
        $videoId = [];
        $actualSection = 0;
        $path          = [];
        $departments = [];

        if(!$department->id && (user()->role == "Owner" || user()->role == "Admin")){
            $department = Department::first();
        }
        
        if($department->id){   
            $actualSection = $section ?? TrainingPageSection::whereDepartmentId($department->id)->first();
            $content       = $this->getContent($actualSection);
            // dd($actualSection);
            $departments   = Department::all();
            $index         = 0; 
            
            if ($content) {
                $videoId = explode('/', $content->video_url);
                $index   = count($videoId);
            }
            
            $path = $this->getPath($actualSection);
        }
            
        return view('castle.manage-trainings.index', [
            'sections'      => $department->id ? $this->getParentSections($actualSection) : [],
            'content'       => $content,
            'videoId'       => $videoId[$index - 1] ?? null,
            'actualSection' => $actualSection,
            'path'          => $path,
            'departmentId'  => $department->id ?? 0,
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

    public function storeSection(TrainingPageSection $section)
    {
        $validated = $this->validate(
            request(),
            [
                'title'     => 'required|string|max:255',
            ],
        );        
        $trainingPageSection                = new TrainingPageSection();
        $trainingPageSection->title         = $validated['title'];
        $trainingPageSection->parent_id     = $section->id;
        $trainingPageSection->department_id = $section->department_id;

        $trainingPageSection->save();

        alert()
            ->withTitle(__('Section created!'))
            ->send();

        return redirect(route('castle.manage-trainings.index', [
            'department' => $$section->department_id,
            'section'    => $section->id
        ]));
    }

    public function updateSection(TrainingPageSection $section)
    {
        $validated = $this->validate(
            request(),
            [
                'title'     => 'required|string|max:255',
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
                'content_title'   => 'required|string|max:255',
                'video_url'       => 'required|string|max:255',
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
        $validated = $this->validate(
            request(),
            [
                'content_title'           => 'required|string|max:255',
                'video_url'               => 'required|string|max:255',
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
        return TrainingPageContent::whereTrainingPageSectionId($section->id)->first();
    }

    public function getParentSections($section)
    {
        return TrainingPageSection::whereParentId($section->id ?? 1)->get();
    }
}
