<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserManagedOfficesTable extends Migration
{
    public function up()
    {
        Schema::create('user_managed_offices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('office_id')->constrained();
            $table->timestamps();

            $table->index(['user_id', 'office_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_managed_offices');
    }
}
