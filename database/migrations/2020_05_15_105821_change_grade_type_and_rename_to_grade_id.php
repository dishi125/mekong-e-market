<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeGradeTypeAndRenameToGradeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('grade');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->integer('grade_id')->after('area_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('grade_id');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->string('grade')->after('area_id');
        });
    }
}
