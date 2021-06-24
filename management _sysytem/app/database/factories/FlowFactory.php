<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Flow;
use Faker\Generator as Faker;

$factory->define(
    Flow::class,
    function (Faker $faker) {
        return [
            'flow_name' => $faker->name,
            'rounds'    => json_encode(["xyz", "abc"]),
        ];
    }
);
