<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Feedback;
use App\User;
use App\Candidacy;
use Faker\Generator as Faker;

$factory->define(
    Feedback::class,
    function (Faker $faker) {
        return [
            'verdict'       => $faker->randomElement(['Yes', 'No', 'Maybe']),
            'description'   => $faker->paragraph,
            'user_id'       => factory(App\User::class)->create()->id,
            'candidacy_id'  => factory(App\Candidacy::class)->create()->id,
        ];
    }
);
