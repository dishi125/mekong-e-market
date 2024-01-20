<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnImportedInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('imported');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->integer('imported')->default(0)->after('other_species');
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
            $table->dropColumn('imported');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->string('imported')->default('')->after('other_species');
        });
    }
}
