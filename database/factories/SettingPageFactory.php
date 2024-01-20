<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SettingPage;
use Faker\Generator as Faker;

$factory->define(SettingPage::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
