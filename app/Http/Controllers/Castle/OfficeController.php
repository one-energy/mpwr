<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;

class OfficeController extends Controller
{
    public function index()
    {
        return view('castle.offices.index');
    }

    public function create()
    {
        $regionsQuery = Region::query()->select("regions.*");
        $usersQuery   = User::query()->whereRole("Office Manager");

        if(user()->role == "Admin" || user()->role == "Owner"){
            $users   = $usersQuery->get();
            $regions = $regionsQuery->get();
        }

        if(user()->role == "Department Manager"){
            $regions =  $regionsQuery
                ->join('departments', function ($join) {
                    $join->on("regions.department_id", '=', 'departments.id')
                        ->where('departments.department_manager_id', '=', user()->id);
                })->get();
            $users =  $usersQuery->whereDepartmentId(user()->department_id)->get();
        }
        if(user()->role == "Region Manager"){
            $regions =  $regionsQuery->whereRegionManagerId(user()->id)->get();
            $users =  $usersQuery->whereDepartmentId(user()->department_id)->get();
        }
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

        return redirect(route('castle.offices.index', $office));
    }

    public function edit(Office $office)
    {
        $regionsQuery = Region::query();
        if(user()->role == "Department Manager"){
            $regions = $regionsQuery->whereDepartmentId(user()->department_id)->get();
        }

        if(user()->role == "Region Manager"){
            $regions = $regionsQuery->whereRegionManagerId(user()->id)->get();
        }

        if(user()->role == "Office Manager"){
            $regions = $regionsQuery->whereId($office->region_id)->get();
        }

        if(user()->role == "Owner" || user()->role == "Admin"){
            $regions = $regionsQuery->get();
        }

        $users   = User::query()
            ->whereDepartmentId($office->region->department->id)
            ->orderBy("first_name")
            ->get();

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

        return redirect(route('castle.offices.index'));
    }

    public function destroy($id)
    {
        Office::destroy($id);

        alert()
            ->withTitle(__('Office has been deleted!'))
            ->send();

        return back();
    }

    public function getOffices($department = null)
    {
        if($department){
            return Office::query()->select("offices.*")
                ->join("regions", "offices.region_id", "=", "regions.id")
                ->where("regions.department_id", "=", $department)
                ->get();
        }
        $department = Department::first()->id;
        return Office::query()->select("offices.*")
            ->join("regions", "offices.region_id", "=", "regions.id")
            ->where("regions.department_id", "=", $department)
            ->get();
    }
}
