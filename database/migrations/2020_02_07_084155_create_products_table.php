<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_profile_id');
            $table->integer('main_category_id');
            $table->integer('sub_category_id');
            $table->integer('specie_id');
            $table->string('other_specie')->nullable();
            $table->string('imported')->nullable();
            $table->string('grade')->nullable();
            $table->string('url')->nullable();
            $table->string('pickup_point');
            $table->text('description');
            $table->boolean('fast_buy')->default(false);
            $table->double('fast_buy_price')->nullable();
            $table->integer('is_mygap')->default(0);
            $table->integer('is_organic')->default(0);
            $table->integer('repost')->default(0);
            $table->timestamp('end_time');
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
        Schema::dropIfExists('products');
    }
}
