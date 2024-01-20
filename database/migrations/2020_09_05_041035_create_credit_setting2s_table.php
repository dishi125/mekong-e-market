<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditSetting2sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_setting2', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('main_category_id');
            $table->string('spices_category');
            $table->double('credit_per_transaction',13,2);
            $table->string('sub_categories');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('credit_setting2');
    }
}
