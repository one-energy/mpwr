<?php

namespace App\Http\Controllers;

use App\Facades\Actions\DestroySection;
use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;

class TrainingController extends Controller
{
    public $trainings = [];

    public function index(Department $department, TrainingPageSection $section = null, $search = null)
    {
        $this->authorize('viewList', [TrainingPageSection::class, $department->id]);

        if (user()->hasRole('Department Manager') && user()->department_id === null) {
            alert()
                ->withTitle(__('You need to be part of a department to access!'))
                ->withColor('red')
                ->send();

            return redirect()->route('home');
        }

        return view('training.index', [
            'department' => $department,
            'section'    => $section,
        ]);
    }

    public function searchTrainings()
    {
        $this->trainings = TrainingPageSection::get();
    }

    public function manageTrainings(Department $department, TrainingPageSection $section = null)
    {
        $this->authorize('viewList', [TrainingPageSection::class, $department->id]);

        if (user()->hasRole('Department Manager') && user()->department_id === null) {
            alert()
                ->withTitle(__('You need to be part of a department to access!'))
                ->withColor('red')
                ->send();

            return redirect()->route('castle.dashboard');
        }

        if (user()->hasRole('Region Manager') && user()->department_id === null) {
            alert()
                ->withTitle(__('You need to be linked to a department!'))
                ->withColor('red')
                ->send();

            return back();
        }

        return view('castle.manage-trainings.index', [
            'department' => $department,
            'section'    => $section,
        ]);
    }

    public function deleteSection(TrainingPageSection $section)
    {
        DestroySection::execute($section);

        return redirect(route('castle.manage-trainings.index', [
            'department' => $section->department_id,
            'section'    => $section->parent_id,
        ]));
    }

    public function storeSection(TrainingPageSection $section)
    {
        if ($section->isDepartmentSection() && user()->hasRole('Region Manager')) {
            alert()
                ->withTitle(__('You can only create content inside of your region section!'))
                ->withColor('red')
                ->send();

            return back();
        }

        request()->validate(['title' => 'required|string|max:255']);

        TrainingPageSection::create([
            'title'             => request()->title,
            'parent_id'         => $section->id,
            'department_id'     => $section->department_id,
            'region_id'         => $section->region_id,
            'department_folder' => $section->department_folder,
        ]);

        alert()
            ->withTitle(__('Section created!'))
            ->send();

        return redirect(route('castle.manage-trainings.index', [
            'department' => $section->department_id,
            'section'    => $section->id,
        ]));
    }

    public function updateSection(TrainingPageSection $section)
    {
        $validated = request()->validate([
            'title' => 'required|string|max:255',
        ]);

        $trainingPageContent        = TrainingPageSection::query()->whereId($section->id)->first();
        $trainingPageContent->title = $validated['title'];
        $trainingPageContent->update();

        alert()
            ->withTitle(__('Section saved!'))
            ->send();

        return redirect(route('castle.manage-trainings.index', [
            'department' => $section->department_id,
            'section'    => $section->parent_id,
        ]));
    }

    public function storeContent(TrainingPageSection $section)
    {
        $validated = request()->validate([
            'content_title' => 'required|string|max:255',
            'video_url'     => 'required|string|max:255',
            'description'   => 'required|string',
        ]);

        $trainingPageContent                           = new TrainingPageContent();
        $trainingPageContent->title                    = $validated['content_title'];
        $trainingPageContent->description              = $validated['description'];
        $trainingPageContent->video_url                = $validated['video_url'];
        $trainingPageContent->training_page_section_id = $section->id;

        $trainingPageContent->save();

        alert()
            ->withTitle(__('Content created!'))
            ->send();

        return redirect(route('castle.manage-trainings.index', [
            'department' => $section->department_id,
            'section'    => $section->id,
        ]));
    }

    public function updateContent(TrainingPageContent $content)
    {
        $validated = request()->validate([
            'content_title' => 'required|string|max:255',
            'video_url'     => 'required|string|max:255',
            'description'   => 'required|string',
        ]);

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
            'section'    => $content->training_page_section_id,
        ]));
    }
}
