<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\TrainingPageSection;
use App\Models\User;

class DepartmentController extends Controller
{
    public function getDepartments()
    {
        return Department::all();
    }

    public function index()
    {
        return view('castle.departments.index');
    }

    public function create()
    {
        $users = User::query()->where('role', 'Department Manager')->get();

        return view('castle.departments.create', compact('users'));
    }

    public function store()
    {
        $validated = $this->validate(
            request(),
            [
                'name'                  => 'required|string|min:3|max:255',
                'department_manager_id' => 'required',
            ],
            [
                'department_id.required'         => 'The department field is required.',
                'department_manager_id.required' => 'The department manager field is required.',
            ],
        );

        $department                        = new Department();
        $department->name                  = $validated['name'];
        $department->department_manager_id = $validated['department_manager_id'];

        $department->save();

        $trainingPage                = new TrainingPageSection();
        $trainingPage->title         = "Training Page";
        $trainingPage->parent_id     = null;
        $trainingPage->department_id = $department->id;
        $trainingPage->save();

        $user                = User::query()->whereId($department->department_manager_id)->first();
        $user->department_id = $department->id;
        $user->save();

        alert()
            ->withTitle(__('Department Created!'))
            ->send();

        return redirect(route('castle.departments.index'));
    }

    public function edit(Department $department)
    {
        $users = User::query()->where('role', 'Department Manager')->get();

        return view('castle.departments.edit', [
            'department' => $department,
            'users'      => $users,
        ]);
    }

    public function update(Department $department)
    {
        $validated = request()->validate([
            'name'                  => 'required|string|min:3|max:255',
            'department_manager_id' => 'required',
        ], [
            'department_id.required'         => 'The department field is required.',
            'department_manager_id.required' => 'The department manager field is required.',
        ]);

        $department->name                  = $validated['name'];
        $department->department_manager_id = $validated['department_manager_id'];

        $department->save();

        alert()
            ->withTitle(__('Department Updated!'))
            ->send();

        return redirect(route('castle.departments.index'));
    }
}
