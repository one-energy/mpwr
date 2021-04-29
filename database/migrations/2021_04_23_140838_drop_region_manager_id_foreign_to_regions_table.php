<?php

use App\Role\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropRegionManagerIdForeignToRegionsTable extends Migration
{
    public function up()
    {
        Schema::table('regions', function (Blueprint $table) {
            $regions = DB::table('regions')
                ->select('region_manager_id', 'id')
                ->get();

            $table->dropConstrainedForeignId('region_manager_id');

            $regions->each(function ($region) {
                $user = DB::table('users')
                    ->where('role', Role::REGION_MANAGER)
                    ->where('id', $region->region_manager_id)
                    ->select('id')
                    ->first();

                if ($user === null) {
                    return;
                }

                DB::table('user_managed_regions')->insert([
                    'user_id'    => $user->id,
                    'region_id'  => $region->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        });
    }

    public function down()
    {
        $regions = DB::table('user_managed_regions')
            ->select('region_id', 'user_id')
            ->get();

        Schema::table('regions', function (Blueprint $table) {
            $table
                ->foreignId('region_manager_id')
                ->nullable()
                ->after('id')
                ->references('id')
                ->on('users');
        });

        $regions->each(function ($region) {
            DB::table('regions')
                ->where('id', $region->region_id)
                ->update(['region_manager_id' => $region->user_id]);
        });

        $regions->each(function ($region) {
            DB::table('user_managed_regions')
                ->where('user_id', $region->user_id)
                ->where('region_id', $region->region_id)
                ->delete();
        });
    }
}
