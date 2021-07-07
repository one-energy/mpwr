<?php

namespace App\Http\Controllers\Castle;

use App\Enum\Role;
use App\Http\Controllers\Controller;
use App\Models\Department;
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
        $users = User::query()->where('role', Role::DEPARTMENT_MANAGER)->with('department')->get();

        return view('castle.departments.create', [
            'users' => $users,
        ]);
    }

    public function store()
    {
        $validated = request()->validate([
            'name'                  => 'required|string|min:3|max:255',
            'department_manager_id' => 'required',
        ], [
            'department_id.required'         => 'The department field is required.',
            'department_manager_id.required' => 'The department manager field is required.',
        ]);

        $department                        = new Department();
        $department->name                  = $validated['name'];
        $department->department_manager_id = $validated['department_manager_id'];

        $department->save();

        $department->trainingPageSections()->create([
            'title' => 'Training Page',
        ]);

        $departmentAdmin                = $department->departmentAdmin;
        $departmentAdmin->department_id = $department->id;
        $departmentAdmin->save();

        alert()
            ->withTitle(__('Department Created!'))
            ->send();

        return redirect()->route('castle.departments.index');
    }

    public function edit(Department $department)
    {
        $users = User::query()->where('role', Role::DEPARTMENT_MANAGER)->with('department')->get();

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
