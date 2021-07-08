<?php

namespace App\Http\Controllers;

use App\Enum\Role;
use App\Facades\Actions\DestroySection;
use App\Models\Department;
use App\Models\TrainingPageContent;
use App\Models\TrainingPageSection;

class TrainingController extends Controller
{
    public function index(Department $department, TrainingPageSection $section = null)
    {
        $this->authorize('viewList', [
            TrainingPageSection::class,
            $department->id,
            $section,
        ]);

        if (user()->department_id === null && user()->hasRole(Role::DEPARTMENT_MANAGER)) {
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

    public function manageTrainings(Department $department, TrainingPageSection $section = null)
    {
        $this->authorize('viewList', [
            TrainingPageSection::class,
            $department->id,
            $section,
        ]);

        if (user()->department_id === null && user()->notHaveRoles([Role::ADMIN])) {
            $isDepartmentManager = user()->hasRole(Role::DEPARTMENT_MANAGER);

            $title = $isDepartmentManager
                ? 'You need to be part of a department to access!'
                : 'You need to be linked to a department!';

            alert()
                ->withTitle(__($title))
                ->withColor('red')
                ->send();

            return redirect()->route('castle.dashboard');
        }

        return view('castle.manage-trainings.index', [
            'department' => $department,
            'section'    => $section,
        ]);
    }

    public function deleteSection(TrainingPageSection $section)
    {
        $this->authorize('delete', $section);

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

    public function updateContent(TrainingPageContent $content)
    {
        $validated = request()->validate([
            'title'       => 'required|string|max:255',
            'video_url'   => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $content->update($validated);

        alert()
            ->withTitle(__('Content saved!'))
            ->send();

        return redirect(route('castle.manage-trainings.index', [
            'department' => $content->section->department_id,
            'section'    => $content->training_page_section_id,
        ]));
    }
}
