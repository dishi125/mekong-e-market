<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMyPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('my_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_profile_id');
            $table->integer('credit_package_id');
            $table->string('transaction_id');
            $table->integer('credit_transaction_id');
            $table->integer('transaction_status')->default(1)->comment("1:padding,2:success,0;field");
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
        Schema::dropIfExists('my_packages');
    }
}
