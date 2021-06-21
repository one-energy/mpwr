<?php

namespace Database\Seeders;

use App\Models\DailyNumber;
use App\Models\Department;
use App\Models\Office;
use App\Models\Region;
use App\Models\TrainingPageSection;
use App\Models\User;
use App\Enum\Role;
use Illuminate\Database\Seeder;

class BootstrapSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'first_name'    => 'Brandon',
            'last_name'     => 'Andra',
            'email'         => 'brandonrandra@gmail.com',
            'role'          => Role::OWNER,
            'department_id' => null,
            'master'        => true,
        ]);

        User::factory()->create([
            'first_name'    => 'Jake',
            'last_name'     => 'Ebert',
            'email'         => 'admin@californiarenewableenergy.org',
            'role'          => Role::ADMIN,
            'department_id' => null,
            'master'        => true,
        ]);

        /** @var User $departmentManager */
        $departmentManager = User::factory()->create([
            'first_name' => 'Jake',
            'last_name'  => 'Ebert',
            'email'      => 'jake.department@californiarenewableenergy.org',
            'role'       => Role::DEPARTMENT_MANAGER,
            'master'     => true,
        ]);

        $department = Department::factory()->create(['name' => 'California Renewable Energy']);
        $departmentManager->managedDepartments()->attach($department->id);

        $departmentManager->department_id = $department->id;
        $departmentManager->save();

        TrainingPageSection::factory()->create([
            'title'         => 'Training Page',
            'department_id' => $department->id,
        ]);

        //Region
        //Kyden Hansen Region
        $kydenRegionManager = User::factory()->create([
            'first_name'    => 'Kyden',
            'last_name'     => 'Hansen',
            'email'         => 'kyden@californiarenewableenergy.org',
            'role'          => Role::REGION_MANAGER,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        /** @var Region $region */
        $region = Region::factory()->create([
            'name'          => 'Kyden Hansen Region',
            'department_id' => $department->id,
        ]);
        $region->managers()->attach($kydenRegionManager->id);

        $palmdaleOfficeManager = User::factory()->create([
            'first_name'    => 'John',
            'last_name'     => 'Doe',
            'email'         => 'john+doe@mail.com',
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        //Palmdale Office
        /** @var Office $palmdaleOffice */
        $palmdaleOffice = Office::factory()->create([
            'name'      => 'Palmdale Office',
            'region_id' => $region->id,
        ]);
        $palmdaleOffice->managers()->attach($palmdaleOfficeManager->id);

        $palmdaleOfficeManager->office_id = $palmdaleOffice->id;
        $palmdaleOfficeManager->save();

        //Victorville Office
        $victorvilleOfficeManager = User::factory()->create([
            'first_name'    => 'Jacob',
            'last_name'     => 'McCord',
            'email'         => 'jacobm@californiarenewableenergy.org',
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        /** @var Office $victorvilleOffice */
        $victorvilleOffice = Office::factory()->create([
            'name'      => 'Victorville Office',
            'region_id' => $region->id,
        ]);
        $victorvilleOffice->managers()->attach($victorvilleOfficeManager->id);

        $victorvilleOfficeManager->office_id = $victorvilleOffice->id;
        $victorvilleOfficeManager->save();

        //San Bernardino Office
        $sanBernardinoOfficeManager = User::factory()->create([
            'first_name'    => 'Hunter',
            'last_name'     => 'Clark',
            'email'         => 'hunter@californiarenewableenergy.org',
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        /** @var Office $sanBernardinoOffice */
        $sanBernardinoOffice = Office::factory()->create([
            'name'      => 'San Bernardino Office',
            'region_id' => $region->id,
        ]);
        $sanBernardinoOffice->managers()->attach($sanBernardinoOfficeManager->id);

        $sanBernardinoOfficeManager->office_id = $sanBernardinoOffice->id;
        $sanBernardinoOfficeManager->save();

        //Stockton Office
        $stocktonOfficeManager = User::factory()->create([
            'first_name'    => 'Cade',
            'last_name'     => 'Cloward',
            'email'         => 'cade@californiarenewableenergy.org',
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        /** @var Office $stocktonOffice */
        $stocktonOffice = Office::factory()->create([
            'name'      => 'Stockton Office',
            'region_id' => $region->id,
        ]);
        $stocktonOffice->managers()->attach($stocktonOfficeManager->id);

        $stocktonOfficeManager->office_id = $stocktonOffice->id;
        $stocktonOfficeManager->save();

        $carsonOfficeManager = User::factory()->create([
            'first_name'    => 'Carson',
            'last_name'     => 'Law',
            'email'         => 'carson@californiarenewableenergy.org',
            'role'          => Role::OFFICE_MANAGER,
            'office_id'     => $stocktonOffice->id,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        //Pittsburg Office
        $pittsburgOfficeManager = User::factory()->create([
            'first_name'    => 'Mana',
            'last_name'     => 'Niu',
            'email'         => 'mana@californiarenewableenergy.org',
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        /** @var Office $pittsburgOffice */
        $pittsburgOffice = Office::factory()->create([
            'name'      => 'Pittsburg Office',
            'region_id' => $region->id,
        ]);
        $pittsburgOffice->managers()->attach($pittsburgOfficeManager->id);

        $pittsburgOfficeManager->office_id = $pittsburgOffice->id;
        $pittsburgOfficeManager->save();

        //Region
        //Jake Meyer Region
        $jakeRegionManager = User::factory()->create([
            'first_name'    => 'Jake',
            'last_name'     => 'Meyen',
            'email'         => 'jakemeyer@californiarenewableenergy.org',
            'role'          => Role::REGION_MANAGER,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        /** @var Region $region */
        $region = Region::factory()->create([
            'name'          => 'Jake Meyer Region ',
            'department_id' => $department->id,
        ]);
        $region->managers()->attach($jakeRegionManager->id);

        $maryRegionManager = User::factory()->create([
            'first_name'    => 'Mary',
            'last_name'     => 'Ann',
            'email'         => 'mary+ann@mail.com',
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        //Fairfield Office
        /** @var Office $fairfieldOffice */
        $fairfieldOffice = Office::factory()->create([
            'name'      => 'Fairfield Office',
            'region_id' => $region->id,
        ]);
        $fairfieldOffice->managers()->attach($maryRegionManager->id);

        $jakeRegionManager->office_id = $fairfieldOffice->id;
        $jakeRegionManager->save();

        //Vacaville Office
        $vacavilleManager = User::factory()->create([
            'first_name'    => 'Brandyn',
            'last_name'     => 'Bailey',
            'email'         => 'brandyn@californiarenewableenergy.org',
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => false,
        ]);

        /** @var Office $vacavilleOffice */
        $vacavilleOffice = Office::factory()->create([
            'name'      => 'Vacaville Office',
            'region_id' => $region->id,
        ]);
        $vacavilleOffice->managers()->attach($vacavilleManager->id);

        $vacavilleManager->office_id = $vacavilleOffice->id;
        $vacavilleManager->save();

        //Cordelia Office
        $cordellaManager = User::factory()->create([
            'first_name'    => 'Sara',
            'last_name'     => 'Meyer',
            'email'         => 'sara@californiarenewableenergy.org',
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => false,
        ]);

        /** @var Office $cordellaOffice */
        $cordellaOffice = Office::factory()->create([
            'name'      => 'Cordella Office',
            'region_id' => $region->id,
        ]);
        $cordellaOffice->managers()->attach($cordellaManager->id);

        $cordellaManager->office_id = $cordellaOffice->id;
        $cordellaManager->save();

        $jacksonManager = User::factory()->create([
            'first_name'    => 'Jackson',
            'last_name'     => 'Meyer',
            'email'         => 'jackson@californiarenewableenergy.org',
            'office_id'     => $cordellaOffice->id,
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => false,
        ]);

        //vallejo Office
        $vallejoManager = User::factory()->create([
            'first_name'    => 'Sean',
            'last_name'     => 'Thatcher',
            'email'         => 'sean@californiarenewableenergy.org',
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => false,
        ]);

        /** @var Office $vallejoOffice */
        $vallejoOffice = Office::factory()->create([
            'name'      => 'Vallejo Office',
            'region_id' => $region->id,
        ]);
        $vallejoOffice->managers()->attach($vallejoManager->id);

        $vallejoManager->office_id = $vallejoOffice->id;
        $vallejoManager->save();

        $tagenManager = User::factory()->create([
            'first_name'    => 'Tagen',
            'last_name'     => 'Wolf',
            'email'         => 'tagen@californiarenewableenergy.org',
            'office_id'     => $vallejoOffice->id,
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => false,
        ]);

        //Region
        //Jake Ebert Region
        $ebertRegionManager = User::factory()->create([
            'first_name'    => 'Jake',
            'last_name'     => 'Ebert',
            'email'         => 'jake@californiarenewableenergy.org',
            'role'          => Role::REGION_MANAGER,
            'department_id' => $department->id,
            'master'        => true,
        ]);

        /** @var Region $region */
        $region = Region::factory()->create([
            'name'          => 'Jake Ebert Region ',
            'department_id' => $department->id,
        ]);
        $region->managers()->attach($ebertRegionManager->id);

        //Fresno Office
        $fresnoManager = User::factory()->create([
            'first_name'    => 'Brandon',
            'last_name'     => 'Reeves',
            'email'         => 'brandonreeves@californiarenewableenergy.org',
            'role'          => Role::OFFICE_MANAGER,
            'department_id' => $department->id,
            'master'        => false,
        ]);

        /** @var Office $fresnoOffice */
        $fresnoOffice = Office::factory()->create([
            'name'      => 'Fresno Office',
            'region_id' => $region->id,
        ]);
        $fresnoOffice->managers()->attach($fresnoManager->id);

        $fresnoManager->office_id = $fresnoOffice->id;
        $fresnoManager->save();

        $jacksonManager = User::factory()->create([
            'first_name'    => 'Zach',
            'last_name'     => 'Murdoch',
            'email'         => 'zach@californiarenewableenergy.org',
            'role'          => Role::OFFICE_MANAGER,
            'office_id'     => $fresnoOffice->id,
            'department_id' => $department->id,
            'master'        => false,
        ]);

        Office::all()
            ->each(function (Office $office) {
                /** @var User $user */
                $user = User::factory()->create([
                    'role'          => 'Setter',
                    'office_id'     => $office->id,
                    'department_id' => $office->region->department_id,
                ]);

                DailyNumber::factory()
                    ->times(30)
                    ->create([
                        'user_id'   => $user->id,
                        'office_id' => $office->id,
                    ]);

                DailyNumber::factory()
                    ->times(30)
                    ->create([
                        'user_id'   => $user->id,
                        'office_id' => $office->id,
                    ]);
            });
    }
}
