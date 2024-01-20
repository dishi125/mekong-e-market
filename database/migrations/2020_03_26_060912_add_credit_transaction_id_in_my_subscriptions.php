<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditTransactionIdInMySubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('my_subscriptions', function (Blueprint $table) {
            $table->integer('credit_transaction_id')->after('security_deposit_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('my_subscriptions', function (Blueprint $table) {
            $table->dropColumn('credit_transaction_id');
        });
    }
}
