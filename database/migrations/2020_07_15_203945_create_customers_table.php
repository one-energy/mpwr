<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->decimal('system_size')->nullable();
            $table->string('bill');
            $table->decimal('pay')->nullable();
            $table->string('financing');
            $table->integer('adders')->nullable();
            $table->decimal('epc')->nullable();
            $table->decimal('commission')->nullable();
            $table->decimal('setter_fee')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('panel_sold')->default(false);

            $table->unsignedBigInteger('setter_id')->nullable();;
            $table->foreign('setter_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

            $table->unsignedBigInteger('opened_by_id');
            $table->foreign('opened_by_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
