<?php

namespace App\Http\Controllers\Castle;

use App\Models\Incentive;
use App\Http\Controllers\Controller;

class ManageIncentivesController extends Controller
{
    public function index()
    {
        $this->authorize('viewList', Incentive::class);

        $incentives = Incentive::query()->get();

        return view('castle.incentives.index', compact('incentives'));
    }

    public function create()
    {
        $this->authorize('create', Incentive::class);

        return view('castle.incentives.create');
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
            ]
        );

        $incentive                    = new Incentive();
        $incentive->number_installs   = $validated['number_installs'];
        $incentive->name              = $validated['name'];
        $incentive->installs_achieved = $validated['installs_achieved'];
        $incentive->installs_needed   = $validated['installs_needed'];
        $incentive->kw_achieved       = $validated['kw_achieved'];
        $incentive->kw_needed         = $validated['kw_needed'];
        
        $incentive->save();

        alert()
            ->withTitle(__('Incentive created!'))
            ->send();

        return redirect(route('castle.settings.incentives.edit', $incentive));
    }

    public function edit(Incentive $incentive)
    {
        $this->authorize('view', Incentive::class);

        return view('castle.incentives.edit', compact('incentive'));
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
            ]
        );

        $incentive->number_installs   = $validated['number_installs'];
        $incentive->name              = $validated['name'];
        $incentive->installs_achieved = $validated['installs_achieved'];
        $incentive->installs_needed   = $validated['installs_needed'];
        $incentive->kw_achieved       = $validated['kw_achieved'];
        $incentive->kw_needed         = $validated['kw_needed'];
        
        $incentive->save();

        alert()
            ->withTitle(__('Incentive updated!'))
            ->send();

        return back();
    }

    public function destroy($id)
    {
        $this->authorize('delete', Incentive::class);
        
        Incentive::destroy($id);

        alert()
            ->withTitle(__('Incentive has been deleted!'))
            ->send();

        return back();
    }
}
