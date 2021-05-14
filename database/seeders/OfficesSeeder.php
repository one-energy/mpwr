<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use App\Role\Role;
use Illuminate\Database\Seeder;

class OfficesSeeder extends Seeder
{
    public function run()
    {
        User::query()
            ->where('role', Role::OFFICE_MANAGER)
            ->each(function (User $user) {
                /** @var Region $region */
                $region = Region::whereHas('managers', function ($query) {
                    $query->whereNull('office_id');
                })->first();

                /** @var Office $office */
                $office = Office::factory()->create(['region_id' => $region->id]);
                $office->managers()->attach($user->id);

                User::query()
                    ->whereIn('id', [$region->managers->first()->id, $user->id])
                    ->update(['office_id' => $office->id]);

                User::query()
                    ->whereIn('role', [Role::SETTER, Role::SALES_REP])
                    ->whereNull('office_id')
                    ->limit(20)
                    ->update(['office_id' => $office->id]);
            });
    }
}
