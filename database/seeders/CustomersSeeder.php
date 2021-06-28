<?php

namespace Database\Seeders;

use App\Enum\Role;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Financing;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    public function run()
    {
        Department::all()->each(fn(Department $department) => $this->createCustomer($department));
    }

    private function createCustomer(Department $department)
    {
        $departmentManager = $this->findManager($department, Role::DEPARTMENT_MANAGER);
        $regionManager     = $this->findManager($department, Role::REGION_MANAGER);
        $officeManager     = $this->findManager($department, Role::OFFICE_MANAGER);

        /** @var Financing $financing */
        $financing = Financing::query()->inRandomOrder()->first();

        User::query()
            ->whereIn('role', [Role::SETTER, Role::SALES_REP])
            ->where('department_id', $department->id)
            ->limit(6)
            ->get()
            ->each(function (User $user) use ($departmentManager, $regionManager, $officeManager, $financing) {
                Customer::factory()->create([
                    'financing_id'          => $financing->id,
                    'setter_id'             => $user->id,
                    'sales_rep_id'          => $departmentManager->id,
                    'opened_by_id'          => $departmentManager->id,
                    'department_manager_id' => $departmentManager->id,
                    'region_manager_id'     => $regionManager->id,
                    'office_manager_id'     => $officeManager->id,
                ]);
            });
    }

    private function findManager(Department $department, string $role): User
    {
        return User::query()
            ->where('role', $role)
            ->where('department_id', $department->id)
            ->first();
    }
}
