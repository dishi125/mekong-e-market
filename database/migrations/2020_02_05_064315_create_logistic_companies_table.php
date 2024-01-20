<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLogisticCompaniesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistic_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('reg_no');
            $table->string('id_no');
            $table->string('contact');
            $table->string('email');
            $table->integer('state_id');
            $table->integer('area_id');
            $table->string('address',500);
            $table->string('description',500);
            $table->string('nursery');
            $table->integer('exporter_status');
            $table->string('profile');
            $table->integer('status');
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
        Schema::drop('logistic_companies');
    }
}
