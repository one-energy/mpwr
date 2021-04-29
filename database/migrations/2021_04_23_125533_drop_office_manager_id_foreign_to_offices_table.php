<?php

use App\Role\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropOfficeManagerIdForeignToOfficesTable extends Migration
{
    public function up()
    {
        Schema::table('offices', function (Blueprint $table) {
            $offices = DB::table('offices')
                ->select('office_manager_id', 'id')
                ->get();

            $table->dropConstrainedForeignId('office_manager_id');

            $offices->each(function ($office) {
                $user = DB::table('users')
                    ->where('role', Role::OFFICE_MANAGER)
                    ->where('id', $office->office_manager_id)
                    ->select('id')
                    ->first();

                if ($user === null) {
                    return;
                }

                DB::table('user_managed_offices')->insert([
                    'user_id'    => $user->id,
                    'office_id'  => $office->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        });
    }

    public function down()
    {
        $offices = DB::table('user_managed_offices')
            ->select('office_id', 'user_id')
            ->get();

        Schema::table('offices', function (Blueprint $table) {
            $table
                ->foreignId('office_manager_id')
                ->nullable()
                ->after('region_id')
                ->references('id')
                ->on('users');
        });

        $offices->each(function ($office) {
            DB::table('offices')
                ->where('id', $office->office_id)
                ->update(['office_manager_id' => $office->user_id]);
        });

        $offices->each(function ($office) {
            DB::table('user_managed_offices')
                ->where('user_id', $office->user_id)
                ->where('office_id', $office->office_id)
                ->delete();
        });
    }
}
