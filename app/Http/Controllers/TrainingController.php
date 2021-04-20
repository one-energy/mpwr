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
        $validated = request()->validate([
            'title' => 'required|string|max:255',
        ]);

        $trainingPageSection                = new TrainingPageSection();
        $trainingPageSection->title         = $validated['title'];
        $trainingPageSection->parent_id     = $section->id;
        $trainingPageSection->department_id = $section->department_id;

        $trainingPageSection->save();

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
