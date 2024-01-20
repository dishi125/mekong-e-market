<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAttributeInEndTimeInProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('end_time');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('end_time')->after('repost')->nullable();
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
            $table->dropColumn('end_time');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->timestamp('end_time')->after('repost');
        });
    }
}
