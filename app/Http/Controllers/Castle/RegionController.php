<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    //
    public function index()
    {
        $regions = Region::all();
        return view('castle.regions.index', compact('regions'));
    }

    public function edit(Region $region)
    {
        $users   = User::query()->where('role', 'Region Manager')->get();

        return view('castle.regions.edit', compact('region', 'users'));
    }

    public function update(Region $region)
    {
        $validated = $this->validate(
            request(),
            [
                'name'              => 'required|string|min:3|max:255',
                'region_manager_id' => 'required',
            ],
            [
                'region_id.required'         => 'The region field is required.',
                'region_manager_id.required' => 'The region manager field is required.',
            ],
        );

        $region->name              = $validated['name'];
        $region->owner_id          = $validated['region_manager_id'];
        
        $region->save();

        alert()
            ->withTitle(__('Region updated!'))
            ->send();

        return back();
    }

    public function destroy($id)
    {
        Region::destroy($id);

        alert()
            ->withTitle(__('Office has been deleted!'))
            ->send();

        return back();
    }

    public function store()
    {
        $validated = $this->validate(
            request(),
            [
                'name'              => 'required|string|min:3|max:255',
                'region_manager_id' => 'required',
            ],
            [
                'region_id.required'         => 'The region field is required.',
                'region_manager_id.required' => 'The region manager field is required.',
            ],
        );

        $region                    = new Region();
        $region->name              = $validated['name'];
        $region->owner_id          = $validated['region_manager_id'];
        
        $region->save();

        alert()
            ->withTitle(__('region created!'))
            ->send();

        return redirect(route('castle.regions.edit', $region));
    }

    public function create()
    {
        $users   = User::query()->where('role', 'Region Manager')->get();

        return view('castle.regions.create', compact('users'));
    }
}
