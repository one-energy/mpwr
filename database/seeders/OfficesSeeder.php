<?php

namespace Database\Seeders;

use App\Models\Office;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;

class OfficesSeeder extends Seeder
{
    public function run()
    {
        User::query()
            ->where('role', 'Office Manager')
            ->each(function (User $user) {
                /** @var Region $region */
                $region = Region::whereHas('regionManager', function ($query) {
                    $query->whereNull('office_id');
                })->first();

                /** @var Office $office */
                $office = Office::factory()->create([
                    'office_manager_id' => $user->id,
                    'region_id'         => $region->id
                ]);

                User::query()
                    ->whereIn('id', [$region->regionManager->id, $user->id])
                    ->update(['office_id' => $office->id]);

                User::query()
                    ->where('role', 'Setter')
                    ->whereNull('office_id')
                    ->limit(10)
                    ->update(['office_id' => $office->id]);
            });
    }
}
