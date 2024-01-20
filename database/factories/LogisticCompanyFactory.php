<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LogisticCompany;
use Faker\Generator as Faker;

$factory->define(LogisticCompany::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'reg_no' => $faker->word,
        'id_no' => $faker->word,
        'contact' => $faker->word,
        'email' => $faker->word,
        'state_id' => $faker->randomDigitNotNull,
        'area_id' => $faker->randomDigitNotNull,
        'description' => $faker->word,
        'nursery' => $faker->word,
        'exporter_status' => $faker->randomDigitNotNull,
        'profile' => $faker->word,
        'status' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
