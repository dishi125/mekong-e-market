<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Banner;
use Faker\Generator as Faker;

$factory->define(Banner::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'contact' => $faker->word,
        'email' => $faker->word,
        'start_date' => $faker->word,
        'banner_link' => $faker->word,
        'banner_photo' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
