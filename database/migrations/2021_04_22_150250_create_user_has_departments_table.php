<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHasDepartmentsTable extends Migration
{
    public function up()
    {
        Schema::create('user_has_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('department_id')->constrained();
            $table->timestamps();

            $table->index(['user_id', 'department_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_has_departments');
    }
}
