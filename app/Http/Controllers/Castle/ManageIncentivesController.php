<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Incentive;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManageIncentivesController extends Controller
{
    public function index()
    {
        $query = Incentive::query()->with([
            'department' => fn(BelongsTo $query) => $query->withTrashed(),
        ]);

        $incentives = user()->hasAnyRole(['Admin', 'Owner'])
            ? $query->get()
            : $query->whereDepartmentId(user()->department_id)->orderBy('number_installs')->get();

        return view('castle.incentives.index', compact('incentives'));
    }

    public function create()
    {
        $departments = Department::all();

        return view('castle.incentives.create', compact('departments'));
    }

    public function store()
    {
        $validated = request()->validate(
            [
                'number_installs' => 'required|integer|max:9999',
                'name'            => 'required|string|min:3|max:255',
                'installs_needed' => 'required|integer|max:9999',
                'kw_needed'       => 'required|integer|max:9999',
                'department_id'   => 'required|integer',
            ]
        );

        $incentive                  = new Incentive();
        $incentive->number_installs = $validated['number_installs'];
        $incentive->name            = $validated['name'];
        $incentive->installs_needed = $validated['installs_needed'];
        $incentive->kw_needed       = $validated['kw_needed'];
        $incentive->department_id   = $validated['department_id'];

        $incentive->save();

        alert()
            ->withTitle(__('Incentive created!'))
            ->send();

        return redirect(route('castle.incentives.index'));
    }

    public function edit(Incentive $incentive)
    {
        $departments = Department::all();

        return view('castle.incentives.edit', compact(['incentive', 'departments']));
    }

    public function update(Incentive $incentive)
    {
        $validated = request()->validate(
            [
                'number_installs' => 'required|integer|max:9999',
                'name'            => 'required|string|min:3|max:255',
                'installs_needed' => 'required|integer|max:9999',
                'kw_needed'       => 'required|integer|max:9999',
                'department_id'   => ['nullable', 'numeric'],
            ]
        );

        $incentive->number_installs = $validated['number_installs'];
        $incentive->name            = $validated['name'];
        $incentive->installs_needed = $validated['installs_needed'];
        $incentive->kw_needed       = $validated['kw_needed'];
        $incentive->department_id   = $validated['department_id'];

        $incentive->save();

        alert()
            ->withTitle(__('Incentive updated!'))
            ->send();

        return redirect(route('castle.incentives.index'));
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
