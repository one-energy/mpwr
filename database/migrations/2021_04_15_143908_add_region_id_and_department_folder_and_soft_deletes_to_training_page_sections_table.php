<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegionIdAndDepartmentFolderAndSoftDeletesToTrainingPageSectionsTable extends Migration
{
    public function up()
    {
        Schema::table('training_page_sections', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->constrained();
            $table->boolean('department_folder')->default(true);
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('training_page_sections', function (Blueprint $table) {
            $table->dropConstrainedForeignId('region_id');
            $table->dropColumn('department_folder');
            $table->dropSoftDeletes();
        });
    }
}
