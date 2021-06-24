<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\QuestionnaireSubmission;
use Faker\Generator as Faker;

$factory->define(QuestionnaireSubmission::class, function (Faker $faker) {
    return [
        "questionnaire_id" => factory(App\Questionnaire::class)->create()->id,
        "candidacy_id" => factory(App\Candidacy::class)->create()->id,
        "metadata" => [
            "Tell me something about yourself"=>"I am a computer engineer who like to explore new innovations in IT wolrd",
            "What is the biggest problem you have faced in your life?"=>"I was lost in forest in a tour at my school times.",
        ],
    ];
});
