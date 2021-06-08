<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    public function getRegions(Department $department)
    {
        return Region::query()
            ->with('department')
            ->when(user()->hasRole('Department Manager'), function (Builder $query) use ($department) {
                $query->whereDepartmentId($department->id);
            })
            ->when(user()->hasRole('Region Manager'), function (Builder $query) {
                $query->whereRegionManagerId(user()->id);
            })
            ->when(user()->hasRole('Office Manager'), function (Builder $query) {
                $query->whereId(user()->office->region->id);
            })
            ->get();
    }

    public function index()
    {
        return view('castle.regions.index', [
            'regions' => Region::all(),
        ]);
    }

    public function create()
    {
        $users = User::query()
            ->where('role', '=', 'Region Manager')
            ->when(user()->role == 'Department Manager', function (Builder $query) {
                $query->where('department_id', '=', user()->department_id);
            })
            ->get();

        return view('castle.regions.create', [
            'departments' => Department::all(),
            'users'       => $users,
        ]);
    }

    public function store()
    {
        $validated = request()->validate([
            'name'              => 'required|string|min:3|max:255',
            'region_manager_id' => 'required|exists:users,id',
            'department_id'     => 'required|exists:departments,id',
        ], [
            'region_id.required'         => 'The region field is required.',
            'region_manager_id.required' => 'The region manager field is required.',
            'department_id.required'     => 'The department field is required.',
        ]);

        DB::transaction(function () use ($validated) {
            /** @var Region */
            $region = Region::create($validated);

            $parentSection = TrainingPageSection::query()
                ->where(function (Builder $query) use ($region) {
                    $query->where('department_id', $region->department_id)
                        ->whereNull('parent_id');
                })
                ->first();

            $region->trainingPageSections()->create([
                'title'             => ucwords($region->name),
                'parent_id'         => $parentSection->id ?? null,
                'department_id'     => $region->department_id,
                'department_folder' => false,
            ]);
        });

        alert()
            ->withTitle(__('Region created!'))
            ->send();

        return redirect(route('castle.regions.index'));
    }

    public function edit(Region $region)
    {
        return view('castle.regions.edit', [
            'region'      => $region,
            'users'       => User::query()->where('role', 'Region Manager')->get(),
            'departments' => Department::all(),
        ]);
    }

    public function update(Region $region)
    {
        $validated = request()->validate([
            'name'              => 'required|string|min:3|max:255',
            'region_manager_id' => 'required',
        ], [
            'region_id.required'         => 'The region field is required.',
            'region_manager_id.required' => 'The region manager field is required.',
        ]);

        $region->name              = $validated['name'];
        $region->region_manager_id = $validated['region_manager_id'];

        $region->save();

        alert()
            ->withTitle(__('Region updated!'))
            ->send();

        return redirect(route('castle.regions.index'));
    }
}
