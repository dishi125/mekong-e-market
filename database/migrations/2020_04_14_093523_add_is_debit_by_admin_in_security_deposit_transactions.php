<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDebitByAdminInSecurityDepositTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('security_deposit_transactions', function (Blueprint $table) {
            $table->integer('is_debit_by_admin')->after('amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('security_deposit_transactions', function (Blueprint $table) {
            $table->dropColumn('is_debit_by_admin');
        });
    }
}
