<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CreditManagement;
use Faker\Generator as Faker;

$factory->define(CreditManagement::class, function (Faker $faker) {

    return [
        'buyer_id' => $faker->randomDigitNotNull,
        'post_id' => $faker->randomDigitNotNull,
        'price' => $faker->word,
        'buyer_fees' => $faker->randomDigitNotNull,
        'transaction_id' => $faker->word,
        'amount' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
