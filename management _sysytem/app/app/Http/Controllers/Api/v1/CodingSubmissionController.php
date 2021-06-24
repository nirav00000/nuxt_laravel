<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Http\Resources\ResponseSuccessResource;
use App\CodingSubmission;

class CodingSubmissionController extends Controller
{
    /**
    @OA\Get(
        path = "/api/v1/coding-submissions/{codingSubmission}/",
        operationId = "getCodingSubmission",
        tags = {"CodingSubmission"},
        summary = "Get a coding submission data",
        description = "Returns coding submission infomation",
        @OA\Parameter(
            name = "codingSubmission",
            description = "Coding submission key",
            required = true,
            in = "path",
            @OA\Schema(
                type = "string"
            )
        ),
        @OA\Response(
            response = 200,
            description = "Sucessfull Retrieval",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    description = "Shows operation status",
                    type = "boolean",
                    default = true,
                ),
                @OA\Property(
                    property = "message",
                    description = "Shows operation message",
                    type = "string",
                    default = "coding submission retrived successfully",
                ),
                @OA\Property(
                    property = "data",
                    description = "coding submission data",
                    @OA\Property(
                        property = "challenge",
                        description = "Return challenge",
                        @OA\Property(
                            property = "title",
                            example = "Two number sum",
                            description = "Problem title"
                        ),
                        @OA\Property(
                            property = "description",
                            description = "Problem description",
                            example = "# Description in markdown"
                        ),
                    ),
                    @OA\Property(
                        property = "candidate",
                        description = "Return candidate information",
                        @OA\Property(
                            property = "name",
                            example = "John",
                            description = "Candiate name"
                        ),
                        @OA\Property(
                            property = "key",
                            example = "AABBCC",
                            description = "Candiate key"
                        ),
                    ),
                    @OA\Property(
                        property = "code",
                        description = "coding related data",
                        @OA\Property(
                            property = "code",
                            description = "user submitted code",
                            example = "function problem() {}"
                        ),
                        @OA\Property(
                            property = "language",
                            description = "coding language",
                            example = "javascript"
                        ),
                    ),
                    @OA\Property(
                        property = "tests",
                        description = "test cases",
                        @OA\Property(
                            property = "total_tests",
                            description = "total test cases in problem",
                            example = "5"
                        ),
                        @OA\Property(
                            property = "passed_tests",
                            description = "total passed tests",
                            example = "4"
                        ),
                        @OA\Property(
                            property = "result",
                            description = "tested test case",
                            @OA\Property(
                                property = "crawled",
                                example = true,
                                type = "boolean",
                                description = "Return true if, all test cases are perform against submitted code"
                            ),
                            @OA\Property(
                                property = "last_result_crawled",
                                example = 4,
                                type = "string",
                                description = "Not necessary"
                            ),
                            @OA\Property(
                                property = "results",
                                description = "result test case",
                                @OA\Items(
                                    @OA\Property(
                                        property = "matches",
                                        example = true,
                                        type = "boolean",
                                        description = "If program output matches with STDOUT"
                                    ),
                                    @OA\Property(
                                        property = "actual",
                                        description = "Program return STDOUT",
                                        example = "Hello"
                                    ),
                                    @OA\Property(
                                        property = "expected",
                                        description = "Program STDOUT should be this.",
                                        example = "Cool"
                                    ),
                                    @OA\Property(
                                        property = "hasError",
                                        description = "Program return errors",
                                        example = true,
                                        type = "boolean",
                                    ),
                                    @OA\Property(
                                        property = "errorMessage",
                                        description = "Error description",
                                        example = "Error description"
                                    ),
                                    @OA\Property(
                                        property = "message",
                                        description = "Message program should be and return this",
                                        example = "expected cool but received hello",
                                    ),
                                    @OA\Property(
                                        property = "outOfResources",
                                        description = "Program has runs more than 4s",
                                        example = false,
                                        type = "boolean",
                                    ),
                                )
                            ),
                        ),
                    ),
                ),
            )
        ),
        @OA\Response(
            response = "4XX",
            description ="Client side error.",
            @OA\JsonContent(ref="#/components/schemas/response5xx"),
        ),
        @OA\Response(
            response = "5XX",
            description ="Server side error.",
            @OA\JsonContent(ref="#/components/schemas/response5xx"),
        ),
    )
    */
    public function show(CodingSubmission $coding_submission)
    {
        if ($coding_submission) {
            // Fetch session
            $session = $coding_submission->codingSession;
            // Fetch challenge
            $challenge = $session->codingChallenge;
            // Fetch candidacy
            $candidacy = $session->candidacy;
            // Fetch candidate
            $candidate = $candidacy->candidate;

            $res = [
                'code' => [
                    'code' => $coding_submission->code,
                    'language' => $coding_submission->language
                ],
                'tests' => [
                    'total_tests' => $coding_submission->total_tests,
                    'passed_tests' => $coding_submission->passed_tests,
                    'result' => json_decode($coding_submission->result)
                ],
                'candidacy' => [
                    'key' => $candidacy->key,
                    'position' => $candidacy->position,
                    'final_status' =>  $candidacy->final_status
                ],
                'challenge' => [
                    'title' => $challenge->title,
                    'description' => $challenge->description
                ],
                'candidate' => [
                    'name' => $candidate->name,
                    'key' => $candidate->key
                ]
            ];

            $payload = new ResponseSuccessResource($res, 'coding submission retrived!');

            return response($payload, Response::HTTP_OK);
        }
    }
}
