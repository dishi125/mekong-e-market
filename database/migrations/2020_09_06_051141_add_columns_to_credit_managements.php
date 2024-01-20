<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCreditManagements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_managements', function (Blueprint $table) {
            $table->double('purchase_price',10,2)->nullable()->after('security_deposit_transaction_id');
            $table->integer('payment_type')->nullable()->comment("1=>credit_card,2=>fpx")->after('purchase_price');
            $table->double('service_fee',10,2)->nullable()->after('payment_type');
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
            //
        });
    }
}
