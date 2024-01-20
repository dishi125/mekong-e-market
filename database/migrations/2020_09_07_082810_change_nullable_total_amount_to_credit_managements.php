<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class ChangeNullableTotalAmountToCreditManagements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_managements', function (Blueprint $table) {
            if (!Type::hasType('double')) {
                Type::addType('double', FloatType::class);
            }
            $table->double('total_amount',10,2)->nullable()->change();
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
