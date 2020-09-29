<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('castle.departments.index');
    }

    public function edit($department)
    {
        $users      = User::query()->where('role', 'Department Manager')->get();
        $department = Department::query()->whereId($department)->first();
        
        return view('castle.departments.edit', compact('department', 'users'));
    }

    public function update($department)
    {
        $department = Department::query()->whereId($department)->first();
        $validated  = $this->validate(
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

        $department->name                           = $validated['name'];
        $department->department_manager_id          = $validated['department_manager_id'];
        
        $department->save();

        alert()
            ->withTitle(__('Department Updated!'))
            ->send();

        return back();
    }

    public function destroy($id)
    {
        Department::destroy($id);

        alert()
            ->withTitle(__('Department has been deleted!'))
            ->send();

        return back();
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

        alert()
            ->withTitle(__('Department Created!'))
            ->send();

        return redirect(route('castle.departments.edit', $department));
    }

    public function create()
    {
        $users   = User::query()->where('role', 'Department Manager')->get();

        return view('castle.departments.create', compact('users'));
    }
}
