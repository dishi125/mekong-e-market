<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CreditPackage;
use Faker\Generator as Faker;

$factory->define(CreditPackage::class, function (Faker $faker) {

    return [
        'amount' => $faker->word,
        'credit' => $faker->word,
        'status' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
