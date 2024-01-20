<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationTypeInBannerPackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banner_packages', function (Blueprint $table) {
            $table->string('duration_type')->after('duration');
            $table->integer('duration')->comment('seconds')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banner_packages', function (Blueprint $table) {
            $table->dropColumn('duration_type');
        });
    }
}
