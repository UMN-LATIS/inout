<?php

use Faker\Generator as Faker;

$factory->define(App\Board::class, function (Faker $faker) {
    return [
        'unit'=> $faker->word,
        'public_title' => $faker->company,
        'announcement_text'=> $faker->sentence,
        'public' => false
    ];
});
