<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreditManagementsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_managements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('buyer_id');
            $table->integer('post_id');
            $table->double('bid_price', 10, 2);
            $table->integer('buyer_fees');
            $table->string('transaction_id');
            $table->double('total_amount', 10, 2);
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
        Schema::drop('credit_managements');
    }
}
