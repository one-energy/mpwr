<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::query()->get();

        return view('castle.offices.index', compact('offices'));
    }

    public function create()
    {
        $regions = Region::all();
        $users   = User::query()->where('role', 'Office Manager')->get();

        return view('castle.offices.create', compact('regions', 'users'));
    }

    public function store()
    {
        $validated = $this->validate(
            request(),
            [
                'name'              => 'required|string|min:3|max:255',
                'region_id'         => 'required',
                'office_manager_id' => 'required',
            ],
            [
                'region_id.required'         => 'The region field is required.',
                'office_manager_id.required' => 'The office manager field is required.',
            ],
        );

        $office                    = new Office();
        $office->name              = $validated['name'];
        $office->region_id         = $validated['region_id'];
        $office->office_manager_id = $validated['office_manager_id'];
        
        $office->save();

        alert()
            ->withTitle(__('Office created!'))
            ->send();

        return redirect(route('castle.offices.edit', $office));
    }

    public function edit(Office $office)
    {
        $regions = Region::all();
        $users   = User::query()->where('role', 'Office Manager')->get();

        return view('castle.offices.edit', compact('office', 'regions', 'users'));
    }

    public function update(Office $office)
    {
        $validated = $this->validate(
            request(),
            [
                'name'              => 'required|string|min:3|max:255',
                'region_id'         => 'required',
                'office_manager_id' => 'required',
            ],
            [
                'region_id.required'         => 'The region field is required.',
                'office_manager_id.required' => 'The office manager field is required.',
            ],
        );

        $office->name              = $validated['name'];
        $office->region_id         = $validated['region_id'];
        $office->office_manager_id = $validated['office_manager_id'];
        
        $office->save();

        alert()
            ->withTitle(__('Office updated!'))
            ->send();

        return back();
    }

    public function destroy($id)
    {
        Office::destroy($id);

        alert()
            ->withTitle(__('Office has been deleted!'))
            ->send();

        return back();
    }
}
