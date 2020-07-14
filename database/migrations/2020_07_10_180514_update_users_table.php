<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('first_name')->after('id')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            $table->string('role')->after('password')->nullable();
            $table->string('office')->after('role')->nullable();
            $table->decimal('pay', 8, 2)->after('office')->nullable();
            $table->softDeletes('deleted_at', 0);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('role');
            $table->dropColumn('office');
            $table->dropColumn('pay');
            $table->dropColumn('deleted_at');
        });
    }
}
