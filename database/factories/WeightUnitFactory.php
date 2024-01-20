<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\WeightUnit;
use Faker\Generator as Faker;

$factory->define(WeightUnit::class, function (Faker $faker) {

    return [
        'unit' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
