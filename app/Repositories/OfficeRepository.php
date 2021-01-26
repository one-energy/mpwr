<?php

declare(strict_types = 1);

namespace App\Repositories;

use App\Models\Department;
use App\Models\Office;

class OfficeRepository
{
    public function getOffices($department = null)
    {
        if ($department) {
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
