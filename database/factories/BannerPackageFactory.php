<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BannerPackage;
use Faker\Generator as Faker;

$factory->define(BannerPackage::class, function (Faker $faker) {

    return [
        'location' => $faker->word,
        'price' => $faker->word,
        'duration' => $faker->word,
        'status' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
