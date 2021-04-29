<?php

use App\Role\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropDepartmentManagerIdForeignToDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $departments = DB::table('departments')
                ->select('department_manager_id', 'id')
                ->get();

            $table->dropConstrainedForeignId('department_manager_id');

            $departments->each(function ($department) {
                $user = DB::table('users')
                    ->where('role', Role::DEPARTMENT_MANAGER)
                    ->where('id', $department->department_manager_id)
                    ->select('id')
                    ->first();

                if ($user === null) {
                    return;
                }

                DB::table('user_managed_departments')->insert([
                    'user_id'       => $user->id,
                    'department_id' => $department->id,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            });
        });
    }

    public function down()
    {
        $departments = DB::table('user_managed_departments')
            ->select('department_id', 'user_id')
            ->get();

        Schema::table('departments', function (Blueprint $table) {
            $table
                ->foreignId('department_manager_id')
                ->nullable()
                ->after('id')
                ->references('id')
                ->on('users');
        });

        $departments->each(function ($department) {
            DB::table('departments')
                ->where('id', $department->department_id)
                ->update(['department_manager_id' => $department->user_id]);
        });

        $departments->each(function ($department) {
            DB::table('user_managed_departments')
                ->where('user_id', $department->user_id)
                ->where('department_id', $department->department_id)
                ->delete();
        });
    }
}
