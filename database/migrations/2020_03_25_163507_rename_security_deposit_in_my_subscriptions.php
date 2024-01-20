<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameSecurityDepositInMySubscriptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('my_subscriptions', function (Blueprint $table) {
            $table->dropColumn('security_deposit');
            $table->integer('security_deposit_transaction_id')->default(0)->after('transaction_id');
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
            $table->dropColumn('security_deposit_transaction_id');
            $table->integer('security_deposit')->after('transaction_id');
        });
    }
}
