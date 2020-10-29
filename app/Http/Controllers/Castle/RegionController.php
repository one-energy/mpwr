<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
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
        $users       = User::query()->where('role', 'Region Manager')->get();
        $departments = Department::all();

        return view('castle.regions.edit', compact('region', 'departments'));
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

        $region->name                       = $validated['name'];
        $region->region_manager_id          = $validated['region_manager_id'];
        
        $region->save();

        alert()
            ->withTitle(__('Region updated!'))
            ->send();

        return redirect(route('castle.regions.index'));
    }

    public function destroy($id)
    {
        Region::destroy($id);

        alert()
            ->withTitle(__('Region has been deleted!'))
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
                'department_id'     => 'required',
            ],
            [
                'region_id.required'         => 'The region field is required.',
                'region_manager_id.required' => 'The region manager field is required.',
                'department_id.required'     => 'The department field is required.',
            ],
        );

        $region                    = new Region();
        $region->name              = $validated['name'];
        $region->region_manager_id = $validated['region_manager_id'];
        $region->department_id     = $validated['department_id'];
        
        $region->save();

        alert()
            ->withTitle(__('Region created!'))
            ->send();

        return redirect(route('castle.regions.index'));
    }

    public function create()
    {
        $usersQuery   = User::query()->whereRole("Region Manager");

        if (user()->role == "Admin" || user()->role == "Owner") {
            $users = $usersQuery->get();
        }
        if (user()->role == "Department Manager") {
            $users = $usersQuery->whereDepartmentId(user()->department_id)->get();
        }
        $departments = Department::all();

        return view('castle.regions.create', compact('users', 'departments'));
    }

    public function getRegions($departmentId = null)
    {
        $regionsQuery = Region::query()
            ->select("regions.*", "departments.name as departmentName")
            ->join("departments", "regions.department_id", '=', 'departments.id');

        if(user()->role == "Admin" || user()->role == "Owner"){
            $regions = $regionsQuery->get();
        }

        if(user()->role == "Department Manager"){
            $regions =  $regionsQuery->whereDepartmentId($departmentId)->get();
            
        }
        
        if(user()->role == "Region Manager"){
            $regions =  $regionsQuery->whereRegionManagerId(user()->id)->get();
            
        }

        if(user()->role == "Office Manager"){
            $regions =  $regionsQuery->where("regions.id", "=", user()->office->region->id)->get();
        }
        return $regions;
    }
}
