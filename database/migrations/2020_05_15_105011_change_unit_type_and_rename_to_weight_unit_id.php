<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUnitTypeAndRenameToWeightUnitId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('unit');
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('weight_unit_id')->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('weight_unit_id');
        });
        Schema::table('posts', function (Blueprint $table) {
            $table->string('unit')->after('qty');
        });
    }
}
