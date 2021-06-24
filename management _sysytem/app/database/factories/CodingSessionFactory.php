<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CodingSession;
use Faker\Generator as Faker;

$factory->define(
    CodingSession::class,
    function (Faker $faker) {
        return [
            'challenge_id' => Factory(App\CodingChallenge::class)->create()->id,
            'candidacy_id' => Factory(App\Candidacy::class)->create()->id,
            'code'         => $faker->randomElement(['x = int(input()) ↵y = int(input()) ↵print(x+y)', 'print(\"Helo\")']),
            'language'     => $faker->randomElement(['python', 'js', 'c', 'cpp']),
        ];
    }
);
