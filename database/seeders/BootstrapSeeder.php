<?php

namespace Database\Seeders;

use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use Illuminate\Database\Seeder;

class BootstrapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            "first_name"    => "Brandon",
            "last_name"     => "Andra",
            "email"         => "brandonrandra@gmail.com",
            'role'          => 'Owner',
            'department_id' => null,
            'master'        => true,
        ]);

        User::factory()->create([
            "first_name"    => "Jake",
            "last_name"     => "Ebert",
            "email"         => "admin@californiarenewableenergy.org",
            'role'          => 'Admin',
            'department_id' => null,
            'master'        => true,
        ]);

        $departmentManager = User::factory()->create([
            "first_name" => "Jake",
            "last_name"  => "Ebert",
            "email"      => "jake.department@californiarenewableenergy.org",
            'role'       => 'Department Manager',
            'master'     => true,
        ]);

        $department = Department::factory()->create([
            "name"                  => "California Renewable Energy",
            "department_manager_id" => $departmentManager->id
        ]);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        TrainingPageSection::factory()->create([
            'title'         => 'Training Page',
            'department_id' => $department->id
        ]);

        //Region
        //Kyden Hansen Region
        $kydenRegionManager = User::factory()->create([
            "first_name"    => "Kyden",
            "last_name"     => "Hansen",
            "email"         => "kyden@californiarenewableenergy.org",
            'role'          => 'Region Manager',
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $region = Region::factory()->create([
            "name"              => "Kyden Hansen Region",
            "department_id"     => $department->id,
            "region_manager_id" => $kydenRegionManager->id
        ]);

        //Palmdale Office
        $vacavilleOffice = Office::factory()->create([
            'name'              => "Palmdale Office",
            'office_manager_id' => $kydenRegionManager->id,
            'region_id'         => $region->id,
        ]);

        $kydenRegionManager->office_id = $vacavilleOffice->id;
        $kydenRegionManager->save();

        //Victorville Office
        $victorvilleOfficeManager = User::factory()->create([
            "first_name"    => "Jacob",
            "last_name"     => "McCord",
            "email"         => "jacobm@californiarenewableenergy.org",
            "role"          => "Office Manager",
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $victorvilleOffice = Office::factory()->create([
            "name"              => "Victorville Office",
            "office_manager_id" => $victorvilleOfficeManager->id,
            "region_id"         => $region->id,
        ]);

        $victorvilleOfficeManager->office_id = $victorvilleOffice->id;
        $victorvilleOfficeManager->save();

        //San Bernardino Office
        $sanBernardinoOfficeManager = User::factory()->create([
            "first_name"    => "Hunter",
            "last_name"     => "Clark",
            "email"         => "hunter@californiarenewableenergy.org",
            "role"          => "Office Manager",
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $sanBernardinoOffice = Office::factory()->create([
            "name"              => "San Bernardino Office",
            "office_manager_id" => $sanBernardinoOfficeManager->id,
            "region_id"         => $region->id,
        ]);

        $sanBernardinoOfficeManager->office_id = $sanBernardinoOffice->id;
        $sanBernardinoOfficeManager->save();

        //Stockton Office
        $stocktonOfficeManager = User::factory()->create([
            "first_name"    => "Cade",
            "last_name"     => "Cloward",
            "email"         => "cade@californiarenewableenergy.org",
            "role"          => "Office Manager",
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $stocktonOffice = Office::factory()->create([
            "name"              => "Stockton Office",
            "office_manager_id" => $stocktonOfficeManager->id,
            "region_id"         => $region->id,
        ]);

        $stocktonOfficeManager->office_id = $stocktonOffice->id;
        $stocktonOfficeManager->save();

        $carsonOfficeManager = User::factory()->create([
            "first_name"    => "Carson",
            "last_name"     => "Law",
            "email"         => "carson@californiarenewableenergy.org",
            "role"          => "Office Manager",
            "office_id"     => $stocktonOffice->id,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        //Pittsburg Office
        $pittsburgOfficeManager = User::factory()->create([
            "first_name"    => "Mana",
            "last_name"     => "Niu",
            "email"         => "mana@californiarenewableenergy.org",
            "role"          => "Office Manager",
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $pittsburgOffice = Office::factory()->create([
            "name"              => "Pittsburg Office",
            "office_manager_id" => $pittsburgOfficeManager->id,
            "region_id"         => $region->id,
        ]);

        $pittsburgOfficeManager->office_id = $pittsburgOffice->id;
        $pittsburgOfficeManager->save();

        //Region
        //Jake Meyer Region
        $jakeRegionManager = User::factory()->create([
            "first_name"    => "Jake",
            "last_name"     => "Meyen",
            "email"         => "jakemeyer@californiarenewableenergy.org",
            'role'          => 'Region Manager',
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $region = Region::factory()->create([
            "name"              => "Jake Meyer Region ",
            "department_id"     => $department->id,
            "region_manager_id" => $jakeRegionManager->id
        ]);

        //Fairfield Office
        $fairfieldOffice = Office::factory()->create([
            'name'              => "Fairfield Office",
            'office_manager_id' => $jakeRegionManager->id,
            'region_id'         => $region->id,
        ]);

        $jakeRegionManager->office_id = $fairfieldOffice->id;
        $jakeRegionManager->save();

        //Vacaville Office
        $vacavilleManager = User::factory()->create([
            'first_name'    => "Brandyn",
            'last_name'     => "Bailey",
            "email"         => "brandyn@californiarenewableenergy.org",
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);

        $vacavilleOffice = Office::factory()->create([
            'name'              => "Vacaville Office",
            'office_manager_id' => $vacavilleManager->id,
            'region_id'         => $region->id,
        ]);

        $vacavilleManager->office_id = $vacavilleOffice->id;
        $vacavilleManager->save();

        //Cordelia Office
        $cordellaManager = User::factory()->create([
            'first_name'    => "Sara",
            'last_name'     => "Meyer",
            "email"         => "sara@californiarenewableenergy.org",
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);

        $cordellaOffice = Office::factory()->create([
            'name'              => "Cordella Office",
            'office_manager_id' => $cordellaManager->id,
            'region_id'         => $region->id,
        ]);

        $cordellaManager->office_id = $cordellaOffice->id;
        $cordellaManager->save();

        $jacksonManager = User::factory()->create([
            'first_name'    => "Jackson",
            'last_name'     => "Meyer",
            "email"         => "jackson@californiarenewableenergy.org",
            "office_id"     => $cordellaOffice->id,
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);

        //vallejo Office
        $vallejoManager = User::factory()->create([
            'first_name'    => "Sean",
            'last_name'     => "Thatcher",
            "email"         => "sean@californiarenewableenergy.org",
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);

        $vallejoOffice = Office::factory()->create([
            'name'              => "Vallejo Office",
            'office_manager_id' => $vallejoManager->id,
            'region_id'         => $region->id,
        ]);

        $vallejoManager->office_id = $vallejoOffice->id;
        $vallejoManager->save();

        $tagenManager = User::factory()->create([
            'first_name'    => "Tagen",
            'last_name'     => "Wolf",
            "email"         => "tagen@californiarenewableenergy.org",
            "office_id"     => $vallejoOffice->id,
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);

        //Region
        //Jake Ebert Region
        $ebertRegionManager = User::factory()->create([
            "first_name"    => "Jake",
            "last_name"     => "Ebert",
            "email"         => "jake@californiarenewableenergy.org",
            'role'          => 'Region Manager',
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $region = Region::factory()->create([
            "name"              => "Jake Ebert Region ",
            "department_id"     => $department->id,
            "region_manager_id" => $ebertRegionManager->id
        ]);

        //Fresno Office
        $fresnoManager = User::factory()->create([
            'first_name'    => "Brandon",
            'last_name'     => "Reeves",
            "email"         => "brandonreeves@californiarenewableenergy.org",
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);

        $fresnoOffice = Office::factory()->create([
            'name'              => "Fresno Office",
            'office_manager_id' => $fresnoManager->id,
            'region_id'         => $region->id,
        ]);

        $fresnoManager->office_id = $fresnoOffice->id;
        $fresnoManager->save();

        $jacksonManager = User::factory()->create([
            'first_name'    => "Zach",
            'last_name'     => "Murdoch",
            "email"         => "zach@californiarenewableenergy.org",
            'role'          => 'Office Manager',
            "office_id"     => $fresnoOffice->id,
            'department_id' => $department->id,
            'master'        => false,
        ]);

        Office::all()
            ->each(function (Office $office) {
                /** @var User $user */
                $user = User::factory()->create([
                    'role'          => 'Setter',
                    'office_id'     => $office->id,
                    'department_id' => $office->region->department_id
                ]);

                DailyNumber::factory()
                    ->times(30)
                    ->create([
                        'user_id'   => $user->id,
                        'office_id' => $office->id
                    ]);

                DailyNumber::factory()
                    ->times(30)
                    ->create([
                        'user_id'   => $user->id,
                        'office_id' => $office->id
                    ]);
            });
    }
}
