<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\Enum\Role;
use App\Rules\UserHasRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class OfficeController extends Controller
{
    public function getOffices($department = null)
    {
        if ($department) {
            return Office::query()->select('offices.*')
                ->join('regions', 'offices.region_id', '=', 'regions.id')
                ->where('regions.department_id', '=', $department)
                ->get();
        }

        return collect([]);
    }

    public function index()
    {
        return view('castle.offices.index');
    }

    public function create()
    {
        $regions = Region::query()
            ->when(user()->hasRole(Role::DEPARTMENT_MANAGER), function (Builder $query) {
                $query->whereIn('department_id', user()->managedDepartments->pluck('id'));
            })
            ->when(user()->hasRole(Role::REGION_MANAGER), function (Builder $query) {
                $query->whereIn('id', user()->managedRegions->pluck('id'));
            })
            ->get();


        $users = User::query()
            ->where('role', Role::OFFICE_MANAGER)
            ->when(user()->hasRole(Role::DEPARTMENT_MANAGER), function ($query) {
                $query->whereIn('department_id', user()->managedDepartments->pluck('id'));
            })
            ->when(user()->hasRole(Role::REGION_MANAGER), function ($query) {
                $query->whereIn('department_id', user()->department_id);
            });

        return view('castle.offices.create', [
            'regions' => $regions,
            'users'   => $users,
        ]);
    }

    public function store()
    {
        request()->validate([
            'name'                 => 'required|string|min:3|max:255',
            'region_id'            => 'required|exists:regions,id',
            'office_manager_ids'   => 'nullable|array',
            'office_manager_ids.*' => ['nullable', 'exists:users,id', new UserHasRole(Role::OFFICE_MANAGER)],
        ], [
            'region_id.required'         => 'The region field is required.',
            'office_manager_id.required' => 'The office manager field is required.',
        ]);

        $office = DB::transaction(function () {
            /** @var Office $office */
            $office = Office::create([
                'name'      => request()->name,
                'region_id' => request()->region_id,
            ]);

            $managerIds = collect(request()->office_manager_ids)->filter();

            if ($managerIds->isNotEmpty()) {
                $office->managers()->attach($managerIds->toArray());
            }

            return $office;
        });

        alert()
            ->withTitle(__('Office created!'))
            ->send();

        return redirect(route('castle.offices.index', $office));
    }

    public function edit(Office $office)
    {
        $regions = Region::query()
            ->with('department')
            ->when(user()->hasRole(Role::DEPARTMENT_MANAGER), function (Builder $query) {
                $query->where('department_id', user()->department_id);
            })
            ->when(user()->hasRole(Role::REGION_MANAGER), function (Builder $query) {
                $query->where('region_manager_id', user()->id);
            })
            ->when(user()->hasRole(Role::OFFICE_MANAGER), function (Builder $query) use ($office) {
                $query->where('id', $office->region_id);
            })
            ->get();

        $users = User::query()
            ->where('department_id', $office->region->department->id)
            ->orderBy('first_name')
            ->get();

        return view('castle.offices.edit', [
            'office'  => $office->load(['managers', 'region']),
            'regions' => $regions,
            'users'   => $users,
        ]);
    }

    public function update(Office $office)
    {
        request()->validate(['name' => 'required|string|min:3|max:255']);

        $office->update(['name' => request()->name]);

        alert()
            ->withTitle(__('Office updated!'))
            ->send();

        return redirect(route('castle.offices.index'));
    }
}
