<?php

use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
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
        factory(User::class)->create([
            "first_name"    => "Brandon",
            "last_name"     => "Andra",
            "email"         => "brandonrandra@gmail.com",
            'role'          => 'Owner',
            'department_id' => null,
            'master'        => true,
        ]);

        factory(User::class)->create([
            "first_name"    => "Jake",
            "last_name"     => "Ebert",
            "email"         => "jake.admin@californiarenewableenergy.org",
            'role'          => 'Admin',
            'department_id' => null,
            'master'        => true,
        ]);

        $department    = factory(Department::class)->create([
            "name" => "California Renewable Energy"
        ]);

        //Region
        //Kyden Hansen Region
        $kydenRegionManager = factory(User::class)->create([
            "first_name"    => "Kyden",
            "last_name"     => "Hansen",
            "email"         => "kyden@californiarenewableenergy.org",
            'role'          => 'Region Manager',
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $region        = factory(Region::class)->create([
            "name"              => "Kyden Hansen Region",
            "department_id"     => $department->id,
            "region_manager_id" => $kydenRegionManager->id
        ]);

        //Palmdale Office        
        $vacavilleOffice = factory(Office::class)->create([
            'name'              => "Palmdale Office",
            'office_manager_id' => $kydenRegionManager->id,
            'region_id'         => $region->id,
        ]);

        $kydenRegionManager->office_id = $vacavilleOffice->id;
        $kydenRegionManager->save();

        //Victorville Office      
        $victorvilleOfficeManager = factory(User::class)->create([
            "first_name" => "Jacob",
            "last_name"  => "McCord",
            "email"      => "jacobm@californiarenewableenergy.org",
            "role"       => "Office Manager",
            'department_id' => $department->id,
            'master'        => true,
        ]);
        
        $victorvilleOffice = factory(Office::class)->create([
            "name"              => "Victorville Office",
            "office_manager_id" => $victorvilleOfficeManager->id,
            "region_id"         => $region->id,
        ]);

        $victorvilleOfficeManager->office_id = $victorvilleOffice->id;
        $victorvilleOfficeManager->save();
        
        //San Bernardino Office      
        $sanBernardinoOfficeManager = factory(User::class)->create([
            "first_name" => "Hunter",
            "last_name"  => "Clark",
            "email"      => "hunter@californiarenewableenergy.org",
            "role"       => "Office Manager",
            'department_id' => $department->id,
            'master'        => true,
        ]);
        
        $sanBernardinoOffice = factory(Office::class)->create([
            "name"              => "San Bernardino Office",
            "office_manager_id" => $sanBernardinoOfficeManager->id,
            "region_id"         => $region->id,
        ]);

        $sanBernardinoOfficeManager->office_id = $sanBernardinoOffice->id;
        $sanBernardinoOfficeManager->save();

        //Stockton Office      
        $stocktonOfficeManager = factory(User::class)->create([
            "first_name" => "Cade",
            "last_name"  => "Cloward",
            "email"      => "cade@californiarenewableenergy.org",
            "role"       => "Office Manager",
            'department_id' => $department->id,
            'master'        => true,
        ]);
        
        $stocktonOffice = factory(Office::class)->create([
            "name"              => "Stockton Office",
            "office_manager_id" => $stocktonOfficeManager->id,
            "region_id"         => $region->id,
        ]);

        $stocktonOfficeManager->office_id = $stocktonOffice->id;
        $stocktonOfficeManager->save();

        $carsonOfficeManager = factory(User::class)->create([
            "first_name"    => "Carson",
            "last_name"     => "Law",
            "email"         => "carson@californiarenewableenergy.org",
            "role"          => "Office Manager",
            "office_id"     => $stocktonOffice->id,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        //Pittsburg Office      
        $pittsburgOfficeManager = factory(User::class)->create([
            "first_name"    => "Mana",
            "last_name"     => "Niu",
            "email"         => "mana@californiarenewableenergy.org",
            "role"          => "Office Manager",
            'department_id' => $department->id,
            'master'        => true,
        ]);
        
        $pittsburgOffice = factory(Office::class)->create([
            "name"              => "Pittsburg Office",
            "office_manager_id" => $pittsburgOfficeManager->id,
            "region_id"         => $region->id,
        ]);

        $pittsburgOfficeManager->office_id = $pittsburgOffice->id;
        $pittsburgOfficeManager->save();

        //Region
        //Jake Meyer Region 
        $jakeRegionManager = factory(User::class)->create([
            "first_name"    => "Jake",
            "last_name"     => "Meyen",
            "email"         => "jakemeyer@californiarenewableenergy.org",
            'role'          => 'Region Manager',
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $region        = factory(Region::class)->create([
            "name"              => "Jake Meyer Region ",
            "department_id"     => $department->id,
            "region_manager_id" => $jakeRegionManager->id
        ]);
        
        //Fairfield Office
        $fairfieldOffice = factory(Office::class)->create([
            'name'              => "Fairfield Office",
            'office_manager_id' => $jakeRegionManager->id,
            'region_id'         => $region->id,
        ]);

        $jakeRegionManager->office_id = $fairfieldOffice->id;
        $jakeRegionManager->save();
        
        //Vacaville Office
        $vacavilleManager = factory(User::class)->create([
            'first_name'    => "Brandyn",
            'last_name'     => "Bailey",
            "email"         => "brandyn@californiarenewableenergy.org",
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);
        
        $vacavilleOffice = factory(Office::class)->create([
            'name'              => "Vacaville Office",
            'office_manager_id' => $vacavilleManager->id,
            'region_id'         => $region->id,
        ]);

        $vacavilleManager->office_id = $vacavilleOffice->id;
        $vacavilleManager->save();

        //Cordelia Office
        $cordellaManager = factory(User::class)->create([
            'first_name'    => "Sara",
            'last_name'     => "Meyer",
            "email"         => "sara@californiarenewableenergy.org",
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);
        
        $cordellaOffice = factory(Office::class)->create([
            'name'              => "Cordella Office",
            'office_manager_id' => $cordellaManager->id,
            'region_id'         => $region->id,
        ]);

        $cordellaManager->office_id = $cordellaOffice->id;
        $cordellaManager->save();

        $jacksonManager = factory(User::class)->create([
            'first_name'    => "Jackson",
            'last_name'     => "Meyer",
            "email"         => "jackson@californiarenewableenergy.org",
            "office_id"     => $cordellaOffice->id,
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);

        //vallejo Office
        $vallejoManager = factory(User::class)->create([
            'first_name'    => "Sean",
            'last_name'     => "Thatcher",
            "email"         => "sean@californiarenewableenergy.org",
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);
        
        $vallejoOffice = factory(Office::class)->create([
            'name'              => "Vallejo Office",
            'office_manager_id' => $vallejoManager->id,
            'region_id'         => $region->id,
        ]);

        $vallejoManager->office_id = $vallejoOffice->id;
        $vallejoManager->save();

        $tagenManager = factory(User::class)->create([
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
        $ebertRegionManager = factory(User::class)->create([
            "first_name"    => "Jake",
            "last_name"     => "Ebert",
            "email"         => "jake@californiarenewableenergy.org",
            'role'          => 'Region Manager',
            'department_id' => $department->id,
            'master'        => true,
        ]);

        $region        = factory(Region::class)->create([
            "name"              => "Jake Ebert Region ",
            "department_id"     => $department->id,
            "region_manager_id" => $ebertRegionManager->id
        ]);

        //Fresno Office
        $fresnoManager = factory(User::class)->create([
            'first_name'    => "Brandon",
            'last_name'     => "Reeves",
            "email"         => "brandonreeves@californiarenewableenergy.org",
            'role'          => 'Office Manager',
            'department_id' => $department->id,
            'master'        => false,
        ]);
        
        $fresnoOffice = factory(Office::class)->create([
            'name'              => "Fresno Office",
            'office_manager_id' => $fresnoManager->id,
            'region_id'         => $region->id,
        ]);

        $fresnoManager->office_id = $fresnoOffice->id;
        $fresnoManager->save();

        $jacksonManager = factory(User::class)->create([
            'first_name'    => "Zach",
            'last_name'     => "Murdoch",
            "email"         => "zach@californiarenewableenergy.org",
            'role'          => 'Office Manager',
            "office_id"     => $fresnoOffice->id,
            'department_id' => $department->id,
            'master'        => false,
        ]);

    }
}
