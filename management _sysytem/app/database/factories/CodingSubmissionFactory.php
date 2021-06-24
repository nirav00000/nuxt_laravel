<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CodingSubmission;
use Faker\Generator as Faker;
use App\CodingChallenge;

$factory->define(
    CodingSubmission::class,
    function (Faker $faker) {
        // We need session ref and test ref
        $session = Factory(App\CodingSession::class)->create();
        $tests   = count(json_decode($session->codingChallenge->tests));
        return [
            'session_id'   => $session->id,
            'language'     => $session->language,
            'code'         => $session->code,
            'total_tests'  => $tests,
            'passed_tests' => 2,
            'result'       => json_encode(
                [
                    "input"         => [[2,7,11,15], 9],
                    "output"        => "[0,1]",
                    "passed"        => true,
                    "actual"        => "[0,1]",
                    "hasError"      => false,
                    "errorMessage"  => "",
                    "outOfResource" => false,
                ]
            ),
        ];
    }
);
