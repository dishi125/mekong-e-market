<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionStatusTransactionCheckedAtCreditTransactionIdSecurityDepositTransactionIdInCreditManagements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_managements', function (Blueprint $table) {
            $table->integer('transaction_status')->after('total_amount')->default(0)->nullable(true)->comment('0:pending 1:success 2:fail');
            $table->dateTime('transaction_checked_at')->after('transaction_status')->nullable(true);
            $table->integer('credit_transaction_id')->after('transaction_id')->default(0)->nullable(true);
            $table->integer('security_deposit_transaction_id')->after('credit_transaction_id')->default(0)->nullable(true)->comment('use in case of late payment');
            $table->string('transaction_id')->default('')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credit_managements', function (Blueprint $table) {
            $table->dropColumn('transaction_status');
            $table->dropColumn('transaction_checked_at');
            $table->dropColumn('credit_transaction_id');
            $table->dropColumn('security_deposit_transaction_id');
        });
    }
}
