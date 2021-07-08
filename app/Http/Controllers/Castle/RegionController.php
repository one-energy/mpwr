<?php

namespace App\Http\Controllers\Castle;

use App\Enum\Role;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use App\Rules\UserHasRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    public function getRegions(Department $department)
    {
        return Region::query()
            ->with('department')
            ->when(user()->hasRole(Role::DEPARTMENT_MANAGER), function (Builder $query) use ($department) {
                $query->whereDepartmentId($department->id);
            })
            ->when(user()->hasRole(Role::REGION_MANAGER), function (Builder $query) {
                $query->whereIn('id', user()->managedRegions->pluck('id'));
            })
            ->when(user()->hasRole(Role::OFFICE_MANAGER), function (Builder $query) {
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
        return view('castle.regions.create', [
            'departments' => Department::all(),
        ]);
    }

    public function store()
    {
        request()->validate([
            'name'                 => 'required|string|min:3|max:255',
            'department_id'        => 'required|exists:departments,id',
            'region_manager_ids'   => 'nullable|array',
            'region_manager_ids.*' => ['nullable', new UserHasRole(Role::REGION_MANAGER)],
        ], [
            'region_id.required'         => 'The region field is required.',
            'region_manager_id.required' => 'The region manager field is required.',
            'department_id.required'     => 'The department field is required.',
        ]);

        DB::transaction(function () {
            /** @var Region */
            $region = Region::create([
                'name'          => request()->name,
                'department_id' => request()->department_id,
            ]);

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

            $managerIds = collect(request()->region_manager_ids)->filter();

            if ($managerIds->isNotEmpty()) {
                $region->managers()->attach($managerIds->toArray());
            }
        });

        alert()
            ->withTitle(__('Region created!'))
            ->send();

        return redirect(route('castle.regions.index'));
    }

    public function edit(Region $region)
    {
        return view('castle.regions.edit', [
            'region'      => $region->load('managers'),
            'users'       => User::query()->where('role', Role::REGION_MANAGER)->get(),
            'departments' => Department::all(),
        ]);
    }

    public function update(Region $region)
    {
        request()->validate(['name' => 'required|string|min:3|max:255']);

        $region->update(['name' => request()->name]);

        alert()
            ->withTitle(__('Region updated!'))
            ->send();

        return redirect(route('castle.regions.index'));
    }
}
