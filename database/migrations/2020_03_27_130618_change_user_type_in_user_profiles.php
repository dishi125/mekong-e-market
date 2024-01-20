<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUserTypeInUserProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->integer('user_type')->after('sub_category_id')->default(0)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            Schema::table('user_profiles', function (Blueprint $table) {
                $table->dropColumn('user_type');
            });
            Schema::table('user_profiles', function (Blueprint $table) {
                $table->string('user_type')->after('sub_category_id')->nullable(true);
            });
        });
    }
}
