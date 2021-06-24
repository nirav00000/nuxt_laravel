<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Stage;
use Faker\Generator as Faker;

$factory->define(
    Stage::class,
    function (Faker $faker) {
        return [
            'name'     => $faker->randomElement($array = ['Personal Interview', 'HR interview', 'Coding Challange', 'Pair Programming', 'Questionnaire']),
            'type'     => $faker->randomElement($array = ['code', 'questioner', 'interview']),
            'metadata' => json_encode(['key1' => 'value1', 'key2' => 'value2']),
        ];
    }
);
