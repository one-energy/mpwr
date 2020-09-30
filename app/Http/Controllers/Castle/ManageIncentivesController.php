<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Incentive;

class ManageIncentivesController extends Controller
{
    public function index()
    {
        if(user()->role == "Admin" || user()->role == "Owner"){
            $incentives = Incentive::all();
        }else{
            $incentives = Incentive::query()->whereDepartmentId(user()->department_id)->orderBy('number_installs')->get();
        }

        return view('castle.incentives.index', compact('incentives'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('castle.incentives.create', compact('departments'));
    }

    public function store()
    {
        $validated = $this->validate(
            request(),
            [
                'number_installs'   => 'required|integer|max:9999',
                'name'              => 'required|string|min:3|max:255',
                'installs_achieved' => 'required|integer|max:9999',
                'installs_needed'   => 'required|integer|max:9999',
                'kw_achieved'       => 'required|integer|max:9999',
                'kw_needed'         => 'required|integer|max:9999',
                'department_id'     => ['nullable', 'numeric'],
            ]
        );

        $incentive                    = new Incentive();
        $incentive->number_installs   = $validated['number_installs'];
        $incentive->name              = $validated['name'];
        $incentive->installs_achieved = $validated['installs_achieved'];
        $incentive->installs_needed   = $validated['installs_needed'];
        $incentive->kw_achieved       = $validated['kw_achieved'];
        $incentive->kw_needed         = $validated['kw_needed'];
        $incentive->department_id         = $validated['department_id'];
        
        $incentive->save();

        alert()
            ->withTitle(__('Incentive created!'))
            ->send();

        return redirect(route('castle.incentives.edit', $incentive));
    }

    public function edit(Incentive $incentive)
    {
        $departments = Department::all();
        return view('castle.incentives.edit', compact(['incentive','departments']));
    }

    public function update(Incentive $incentive)
    {
        $validated = $this->validate(
            request(),
            [
                'number_installs'   => 'required|integer|max:9999',
                'name'              => 'required|string|min:3|max:255',
                'installs_achieved' => 'required|integer|max:9999',
                'installs_needed'   => 'required|integer|max:9999',
                'kw_achieved'       => 'required|integer|max:9999',
                'kw_needed'         => 'required|integer|max:9999',
                'department_id'     => ['nullable', 'numeric'],
            ]
        );

        $incentive->number_installs   = $validated['number_installs'];
        $incentive->name              = $validated['name'];
        $incentive->installs_achieved = $validated['installs_achieved'];
        $incentive->installs_needed   = $validated['installs_needed'];
        $incentive->kw_achieved       = $validated['kw_achieved'];
        $incentive->kw_needed         = $validated['kw_needed'];
        $incentive->department_id     = $validated['department_id'];
        
        $incentive->save();

        alert()
            ->withTitle(__('Incentive updated!'))
            ->send();

        return back();
    }

    public function destroy($id)
    {
        Incentive::destroy($id);

        alert()
            ->withTitle(__('Incentive has been deleted!'))
            ->send();

        return back();
    }
}
