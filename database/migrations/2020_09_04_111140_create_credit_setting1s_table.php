<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditSetting1sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_setting1', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('main_category_id');
            $table->decimal('hot_species_credit',13,2);
            $table->decimal('mid_species_credit',13,2);
            $table->decimal('low_species_credit',13,2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_setting1');
    }
}
