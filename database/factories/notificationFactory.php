<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Notification;
use Faker\Generator as Faker;

$factory->define(notification::class, function (Faker $faker) {

    return [
        'user_type' => $faker->randomDigitNotNull,
        'type_id' => $faker->randomDigitNotNull,
        'user_id' => $faker->randomDigitNotNull,
        'title' => $faker->word,
        'description' => $faker->word,
        'date' => $faker->date('Y-m-d H:i:s'),
        'status' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
