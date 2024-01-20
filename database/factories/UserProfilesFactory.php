<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\UserProfiles;
use Faker\Generator as Faker;

$factory->define(UserProfiles::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'email' => $faker->word,
        'password' => $faker->word,
        'profile_pic' => $faker->word,
        'phone_no' => $faker->word,
        'user_type' => $faker->word,
        'main_category_id' => $faker->randomDigitNotNull,
        'company_name' => $faker->randomDigitNotNull,
        'company_reg_no' => $faker->randomDigitNotNull,
        'company_tel_no' => $faker->word,
        'state_id' => $faker->randomDigitNotNull,
        'area_id' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
