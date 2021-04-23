<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\Role\Role;
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
        $regionsQuery = Region::query()->select('regions.*');
        $usersQuery   = User::query()->whereRole('Office Manager');

        if (user()->role == 'Admin' || user()->role == 'Owner') {
            $users   = $usersQuery->get();
            $regions = $regionsQuery->get();
        }

        if (user()->role == 'Department Manager') {
            $regions = $regionsQuery
                ->join('departments', function ($join) {
                    $join->on('regions.department_id', '=', 'departments.id')
                        ->where('departments.department_manager_id', '=', user()->id);
                })->get();
            $users   = $usersQuery->whereDepartmentId(user()->department_id)->get();
        }
        if (user()->role == 'Region Manager') {
            $regions = $regionsQuery->whereRegionManagerId(user()->id)->get();
            $users   = $usersQuery->whereDepartmentId(user()->department_id)->get();
        }

        return view('castle.offices.create', compact('regions', 'users'));
    }

    public function store()
    {
        request()->validate([
            'name'                 => 'required|string|min:3|max:255',
            'region_id'            => 'required|exists:regions,id',
            'office_manager_ids'   => 'required|array',
            'office_manager_ids.*' => ['required', 'exists:users,id', new UserHasRole(Role::OFFICE_MANAGER)],
        ], [
            'region_id.required'         => 'The region field is required.',
            'office_manager_id.required' => 'The office manager field is required.',
        ]);

        DB::transaction(function () {
            /** @var Office $office */
            $office = Office::create([
                'name'      => request()->name,
                'region_id' => request()->region_id,
            ]);

            $office->managers()->attach(request()->office_manager_ids);
        });

        alert()
            ->withTitle(__('Office created!'))
            ->send();

        return redirect(route('castle.offices.index', $office));
    }

    public function edit(Office $office)
    {
        $regions = Region::query()
            ->when(user()->role == 'Department Manager', function (Builder $query) {
                $query->where('department_id', '=', user()->department_id);
            })
            ->when(user()->role == 'Region Manager', function (Builder $query) {
                $query->where('region_manager_id', '=', user()->id);
            })
            ->when(user()->role == 'Office Manager', function (Builder $query) use ($office) {
                $query->where('id', '=', $office->region_id);
            })
            ->get();

        $users = User::query()
            ->where('department_id', '=', $office->region->department->id)
            ->orderBy('first_name')
            ->get();

        return view('castle.offices.edit', [
            'office'  => $office,
            'regions' => $regions,
            'users'   => $users,
        ]);
    }

    public function update(Office $office)
    {
        $validated = request()->validate([
            'name'              => 'required|string|min:3|max:255',
            'region_id'         => 'required',
            'office_manager_id' => 'required',
        ], [
            'region_id.required'         => 'The region field is required.',
            'office_manager_id.required' => 'The office manager field is required.',
        ]);

        $office->name              = $validated['name'];
        $office->region_id         = $validated['region_id'];
        $office->office_manager_id = $validated['office_manager_id'];

        $office->save();

        alert()
            ->withTitle(__('Office updated!'))
            ->send();

        return redirect(route('castle.offices.index'));
    }
}
