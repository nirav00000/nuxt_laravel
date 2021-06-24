<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Candidate;
use Faker\Generator as Faker;

$factory->define(
    Candidate::class,
    function (Faker $faker) {
        return [
            'name'     => $faker->name,
            'email'    => $faker->safeEmail,
            'metadata' => [
                'contact_no'   => $faker->phoneNumber,
                'education'    => 'Education TEST BE',
                'college'      => 'Collge TEST',
                'experience'   => $faker->numberBetween($min = 0, $max = 1000),
                'last_company' => $faker->company,
            ],
        ];
    }
);
