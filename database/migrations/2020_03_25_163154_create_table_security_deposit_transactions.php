<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSecurityDepositTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_deposit_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('type')->comment("0:Credit-1:Debit");
            $table->integer('user_profile_id');
            $table->double('amount');
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
        Schema::dropIfExists('security_deposit_transactions');
    }
}
