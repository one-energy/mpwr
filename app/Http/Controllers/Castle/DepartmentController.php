<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Role\Role;
use App\Rules\UserHasRole;
use Illuminate\Support\Facades\DB;

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

        return view('castle.departments.create', [
            'users' => $users,
        ]);
    }

    public function store()
    {
        request()->validate([
            'name'                     => 'required|string|min:3|max:255',
            'department_manager_ids'   => 'required|array',
            'department_manager_ids.*' => ['required', 'exists:users,id', new UserHasRole(Role::DEPARTMENT_MANAGER)],
        ], [
            'department_id.required'         => 'The department field is required.',
            'department_manager_id.required' => 'The department manager field is required.',
        ]);

        DB::transaction(function () {
            /** @var Department $department */
            $department = Department::create(['name' => request()->name]);

            $department->managers()->attach(request()->department_manager_ids);
            $department->trainingPageSections()->create(['title' => 'Training Page']);
        });

        alert()
            ->withTitle(__('Department Created!'))
            ->send();

        return redirect()->route('castle.departments.index');
    }

    public function edit(Department $department)
    {
        return view('castle.departments.edit', [
            'department' => $department->load('managers'),
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
