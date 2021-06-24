<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CodingChallenge;
use Faker\Generator as Faker;

$factory->define(
    CodingChallenge::class,
    function (Faker $faker) {
        return [
            'title'       => $faker->randomElement(['Two Sum', 'Three sum', 'Four sum']),
            'description' => $faker->randomElement(['Two Sum description', 'Three sum description', 'Four sum description']),
            'tests'       => json_encode([['inputs' => '2, 3', 'output' => '5'], ['inputs' => '5, 3', 'output' => '8']]),
        ];
    }
);
