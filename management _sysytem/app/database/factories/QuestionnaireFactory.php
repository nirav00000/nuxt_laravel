<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Questionnaire;
use Faker\Generator as Faker;

$factory->define(Questionnaire::class, function (Faker $faker) {
    return [
        'name'=>$faker->randomElement(['Devops Questionnaire','Software Engineer Questionnaire']),
        'metadata'     => [
            "Tell me something about yourself",
            "What is the biggest problem you have faced in your life?" ,
        ],
    ];
});
