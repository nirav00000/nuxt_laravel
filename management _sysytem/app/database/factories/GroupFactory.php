<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(
    App\Group::class,
    function (Faker $faker) {
        return [
            'user_id'   => function () {
                return factory(App\User::class)->create()->id;
            },
            'name'      => $faker->words(3, true),
            'vendor_id' => rand(1000000000, 9999999999),
            'config'    => [
                'api_key' => Str::random(16),
            ],
        ];
    }
);
