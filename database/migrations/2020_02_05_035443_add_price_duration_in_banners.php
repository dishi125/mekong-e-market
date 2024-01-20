<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceDurationInBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('location')->after('email');
            $table->double('price',10,2)->after('location');
            $table->integer('duration')->comment('minutes')->after('price');
            $table->dropColumn('banner_package_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('location');
            $table->dropColumn('price');
            $table->dropColumn('duration');
            $table->integer('banner_package_id')->after('id');
        });
    }
}
