<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string ('name')->nullable();
            $table->string ('email')->nullable();
            $table->string ('password')->nullable();
            $table->string ('profile_pic')->nullable();
            $table->string ('phone_no');
            $table->string ('user_type')->nullable();
            $table->integer('main_category_id')->nullable();
            $table->string ('company_name')->nullable();
            $table->string ('company_reg_no')->nullable();
            $table->string ('company_tel_no')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('area_id')->nullable();
            $table->string ('address')->nullable();
            $table->string ('company_email')->nullable();
            $table->string ('document')->nullable();
            $table->integer('preferred_status')->default(0);
            $table->integer('is_approved_status')->default(0);
            $table->integer('parent_id')->default(0);
            $table->integer('package_id')->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
}
