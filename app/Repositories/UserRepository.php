<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function getRolesPerUrserRole(User $user)
    {
        if (user()->role == "Admin") {
            $roles = [
                ['title' => 'VP',               'name' => 'Department Manager', 'description' => 'Allows update all in departments and Region\'s Number Tracker'],
                ['title' => 'Regional Manager', 'name' => 'Region Manager',     'description' => 'Allows update all Region\'s Number Tracker'],
                ['title' => 'Manager',          'name' => 'Office Manager',     'description' => 'Allows update a Region\'s Number Tracker'],
                ['title' => 'Sales Rep',        'name' => 'Sales Rep',          'description' => 'Allows read/add/edit/cancel Customer'],
                ['title' => 'Setter',           'name' => 'Setter',             'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }
        if (user()->role == "Department Manager") {
            $roles = [
                ['title' => 'Regional Manager', 'name' => 'Region Manager', 'description' => 'Allows update all Region\'s Number Tracker'],
                ['title' => 'Manager',          'name' => 'Office Manager', 'description' => 'Allows update a Region\'s Number Tracker'],
                ['title' => 'Sales Rep',        'name' => 'Sales Rep',      'description' => 'Allows read/add/edit/cancel Customer'],
                ['title' => 'Setter',           'name' => 'Setter',         'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }
        if (user()->role == "Region Manager") {
            $roles = [
                ['title' => 'Manager',   'name' => 'Office Manager', 'description' => 'Allows update a Region\'s Number Tracker'],
                ['title' => 'Sales Rep', 'name' => 'Sales Rep',      'description' => 'Allows read/add/edit/cancel Customer'],
                ['title' => 'Setter',    'name' => 'Setter',         'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }
        if (user()->role == "Office Manager") {
            $roles = [
                ['title' => 'Sales Rep', 'name' => 'Sales Rep', 'description' => 'Allows read/add/edit/cancel Customer'],
                ['title' => 'Setter',    'name' => 'Setter',    'description' => 'Allows see the dashboard and only read Customer'],
            ];
        }

        if ($user->role == "Owner") {
            $roles   = User::ROLES;
        }

        return $roles;
    }

    public function userManageOffices(User $user)
    {
        $offices = Office::whereOfficeManagerId($user->id)->get();
        return count($offices) > 0 ? $offices : false;
    }

    public function userManageRegion(User $user)
    {
        $regions = Region::whereRegionManagerId($user->id)->get();
        return count($regions) > 0 ? $regions : false;
    }

    public function userManageDepartment(User $user)
    {
        $departments = Department::whereDepartmentManagerId($user->id)->get();
        return count($departments) > 0 ? $departments : false;
    }
}
