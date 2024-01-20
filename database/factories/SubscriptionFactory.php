<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Subscription;
use Faker\Generator as Faker;

$factory->define(Subscription::class, function (Faker $faker) {

    return [
        'package_name' => $faker->word,
        'price' => $faker->word,
        'description' => $faker->text,
        'credit' => $faker->word,
        'security_deposit' => $faker->word,
        'sub_user' => $faker->word,
        'bidding' => $faker->word,
        'status' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
