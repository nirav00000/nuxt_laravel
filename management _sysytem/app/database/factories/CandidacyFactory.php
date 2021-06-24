<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Candidacy;
use Faker\Generator as Faker;

$factory->define(
    Candidacy::class,
    function (Faker $faker) {
        return [
            'final_status' => $faker->randomElement(['active', 'inactive']),
            'position'     => $faker->randomElement(['Software Engineer', 'Devops', 'Team Leader', 'Sr.Software Engineer']),
            'candidate_id' => factory(App\Candidate::class)->create()->id,
            'metadata'     => [
                "stages"    => ["key1" => "data1","key2" => "data2"],
                "feedbacks" => ["key1" => "data1","key2" => "data2"],
            ],
        ];
    }
);
