<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CandidacyHistory;
use Faker\Generator as Faker;
use App\Candidacy;
use Carbon\Carbon;


$factory->define(
    CandidacyHistory::class,
    function (Faker $faker) {
        return [
            'candidacy_id' => factory(App\Candidacy::class)->create()->id,
            'stage_name'   => $faker->randomElement(['Software Engineer Interview', 'Devops Interview', 'Team Leader Questioner']),
            'status'       => $faker->randomElement(['created', 'started', 'completed']),
            'actor'     => $faker->name,
            'metadata'         => ['interviewDate' => Carbon::now()->toDateString(), 'duration' => '2hour'],

        ];
    }
);
