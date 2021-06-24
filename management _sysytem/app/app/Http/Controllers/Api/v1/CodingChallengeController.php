<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\CodingChallenge;
use App\Http\Resources\ResponseSuccessResource;
use App\Http\Resources\CodingChallengeResource;
use App\Http\Resources\CodingChallengeDetailResource;

class CodingChallengeController extends Controller
{


    /**
    @OA\Get(
        path = "/api/v1/coding-challenges?page=1",
        operationId = "getCodingChallenges",
        tags = {"CodingChallenge"},
        summary = "Get list of conding challenges",
        description = "Returns list of coding challenges",
        @OA\Response(
            response = 201,
            description = "Successfull Retrieval",
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
                    default = "Coding challenges retrieved successfully"
                ),
                @OA\Property(
                    property = "data",
                    description = "All coding challenges array",
                    @OA\Property(
                        property="challenges",
                        @OA\Items(
                            type="object",
                            ref="#/components/schemas/CodingChallengeSchema",
                        ),
                    ),
                    @OA\Property(
                        property = "meta",
                        ref="#/components/schemas/paginationMeta",
                    ),
                )
            ),
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
    ),
     */
    public function index()
    {
        $response = CodingChallenge::paginate(10);

        // Decompose pagination metadata
        $meta = self::getPagination($response);

        $response  = new ResponseSuccessResource(
            [
                // Get nessesary data
                'challenges' => CodingChallengeResource::collection($response),
                'meta'       => $meta,
            ],
            "Coding challenges retrieved successfully"
        );

        return response($response, Response::HTTP_OK);
    }


    /**
    @OA\Post(
        path = "/api/v1/coding-challenges/",
        operationId = "createCodingChallenge",
        tags = {"CodingChallenge"},
        summary = "Creates a new coding chellenge",
        description = "Returns key with coding challenge id",
        @OA\RequestBody(
            required = true,
           @OA\MediaType(
               mediaType="application/json",
               @OA\Schema(
                   @OA\Property(description = "Coding challenge title", property="title", example= "Two number sum", type="string"),
                   @OA\Property(description = "Coding challenge description", property="description", example= "Write a program that example..", type="string"),
                   @OA\Property(
                    property = "tests",
                    description = "Standard inputs (STDIN), Standard output (STDOUT) string that we expect program should return.",
                        @OA\Items(
                            type="object",
                            @OA\Property(
                                property = "inputs",
                                description = "STDIN inputs ",
                                example = "2, 3"
                            ),
                            @OA\Property(
                                property = "output",
                                description = "STDOUT string",
                                example = "5"
                            ),
                        ),
                   )
                ),
           ),
        ),
        @OA\Response(
            response = "201",
            description = "Challenge created",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    example = true,
                    type = "boolean",
                    description = "Operation status"
                ),
                @OA\Property(
                    property = "message",
                    type="string",
                    example = "Challenge created",
                    description = "Operation message"
                ),
                @OA\Property(
                    property = "data",
                    description = "Coding challenge id",
                    @OA\Items(
                        @OA\Property(
                            property = "key",
                            example = "0UhDnbxtsLxP47hm"
                        )
                    )
                ),
            )
        ),
        @OA\Response(
            response = "4XX",
            description ="Client side error.",
            @OA\JsonContent(ref="#/components/schemas/response4xx"),
        ),
        @OA\Response(
            response = "5XX",
            description ="Server side error.",
            @OA\JsonContent(ref="#/components/schemas/response5xx"),
        )
    )
     */
    public function store(Request $request)
    {
        // Validate title, description, and tests
        $request->validate(
            [
                'title'          => 'required',
                'description'    => 'required',
                'tests'          => 'required|array',
                'tests.*.inputs' => 'string|required',
                'tests.*.output' => 'string|required',
            ]
        );

        // Creating a challenge
        $challenge        = new CodingChallenge();
        $challenge->title = $request->input('title');
        $challenge->description = $request->input('description');
        $challenge->tests       = json_encode($request->input('tests'));
        $challenge->save();

        // Payload to JSON and additional infomation
        $payload   = new ResponseSuccessResource(['key' => $challenge['key']], "Challenge created");

        return response($payload, Response::HTTP_CREATED);
    }


    /**
    @OA\Get(
        path = "/api/v1/coding-challenges/{codingChallenge}/",
        operationId = "getCodingChallenge",
        tags = {"CodingChallenge"},
        summary = "Get a coding challenge data",
        description = "Returns coding challenges infomation",
        @OA\Parameter(
            name = "codingChallenge",
            description = "Coding challenge key",
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
                    default = "coding Challenge retrived successfully",
                ),
                @OA\Property(
                    property = "data",
                    description = "coding challenge data",
                    ref="#/components/schemas/CodingChallengeDetailed",
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
    public function show(CodingChallenge $coding_challenge)
    {
        if ($coding_challenge) {
            $response = new ResponseSuccessResource(new CodingChallengeDetailResource($coding_challenge), "Coding Challenge retrived successfully");
            return response($response, Response::HTTP_OK);
        }
    }


    /**
     @OA\Put(
        path = "/api/v1/coding-challenges/{codingchallenge}",
        operationId = "updateCodingChallenge",
        tags = {"CodingChallenge"},
        summary = "Update the whole challenge (all fields)",
        description = "Required all fields to Update the whole challenge",
        @OA\Parameter(
            name = "coding challenge",
            description = "challenge key",
            required = true,
            in = "path",
            @OA\Schema(
                type = "string"
            )
        ),
         @OA\RequestBody(
            required = true,
            @OA\MediaType(
                mediaType="application/json",
                @OA\Schema(
                    @OA\Property(description = "Coding challenge title", property="title", example= "Two number sum", type="string"),
                    @OA\Property(description = "Coding challenge description", property="description", example= "Write a program that example..", type="string"),
                    @OA\Property(
                    property = "tests",
                    description = "STDIN supply using array, STDOUT has string",
                        @OA\Items(
                            type="object",
                            @OA\Property(
                                property = "inputs",
                                description = "STDIN inputs ",
                                example = "5, 3"
                            ),
                            @OA\Property(
                                property = "output",
                                description = "STDOUT string",
                                example = "8"
                            ),
                        ),
                   )
                ),
            ),
         ),
        @OA\Response(
            response = "201",
            description = "Challenge updated",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    example = true,
                    type = "boolean",
                    description = "Challenge status"
                ),
                @OA\Property(
                    property = "message",
                    type="string",
                    example = "Challenge updated",
                    description = "Challenge message"
                ),
                @OA\Property(
                    property = "data",
                    description = "Challenge key",
                    @OA\Items(
                        @OA\Property(
                            property = "key",
                            example = "0UhDnbxtsLxP47hm"
                        )
                    )
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
    ),

    @OA\Patch(
        path = "/api/v1/coding-challenges/{codingchallenge}",
        operationId = "patchCodingChallenge",
        tags = {"CodingChallenge"},
        summary = "Update a coding challenge (all fields or specified fields)",
        description = "Updates specified fields of the challenge",
        @OA\Parameter(
            name = "coding challenge",
            description = "challenge key",
            required = true,
            in = "path",
            @OA\Schema(
                type = "string"
            )
        ),
         @OA\RequestBody(
            required = false,
            @OA\MediaType(
                mediaType="application/json",
                @OA\Schema(
                    @OA\Property(description = "Challenge title", property="title", example= "Two number sum", type="string"),
                    @OA\Property(description = "Challenge description", property="description", example= "Write a program that example..", type="string"),
                    @OA\Property(
                    property = "tests",
                    description = "STDIN supply using array, STDOUT has string",
                        @OA\Items(
                            type="object",
                            @OA\Property(
                                property = "inputs",
                                description = "STDIN inputs ",
                                example = "2, 3"
                            ),
                            @OA\Property(
                                property = "output",
                                description = "STDOUT string",
                                example = "5"
                            ),
                        ),
                   )
                ),
            ),
         ),
        @OA\Response(
            response = "201",
            description = "Challenge updated",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    example = true,
                    type = "boolean",
                    description = "Challenge status"
                ),
                @OA\Property(
                    property = "message",
                    type="string",
                    example = "Challenge updated",
                    description = "Challenge message"
                ),
                @OA\Property(
                    property = "data",
                    description = "Challenge key",
                    @OA\Items(
                        @OA\Property(
                            property = "key",
                            example = "0UhDnbxtsLxP47hm"
                        )
                    )
                ),
            )
        ),
        @OA\Response(
            response = "5XX",
            description ="Server side error.",
            @OA\JsonContent(ref="#/components/schemas/response5xx"),
        ),
         @OA\Response(
            response = "4XX",
            description ="Client side error.",
            @OA\JsonContent(ref="#/components/schemas/response5xx"),
        ),
    )
     */
    public function update(Request $request, CodingChallenge $coding_challenge)
    {
        $method    = $request->method();
        $challenge = $coding_challenge;

        // Update whole part
        if ($method == "PUT") {
            $request->validate(
                [
                    'title'          => 'required',
                    'description'    => 'required',
                    'tests'          => 'required|array',
                    'tests.*.inputs' => 'string|required',
                    'tests.*.output' => 'string|required',
                ]
            );

            $challenge->title       = $request->input('title');
            $challenge->description = $request->input('description');
            $challenge->tests       = $request->input('tests');
        } else if ($method == "PATCH") { // Update specific given part
            $title       = $request->input('title');
            $description = $request->input('description');
            $tests       = $request->input('tests');

            if ($title) {
                $request->validate(
                    [
                        'title'          => 'required',
                    ]
                );
                $challenge->title = $title;
            }

            if ($description) {
                $request->validate(
                    [
                        'description'          => 'required',
                    ]
                );
                $challenge->description = $description;
            }

            if ($tests) {
                $request->validate(
                    [
                        'tests'          => 'required|array',
                        'tests.*.inputs' => 'string|required',
                        'tests.*.output' => 'string|required',
                    ]
                );
                $challenge->tests = $tests;
            }
        }//end if

        $challenge->save();

        $payload = new ResponseSuccessResource(['key' => $challenge['key']], "Challenge updated");

        return response($payload, Response::HTTP_OK);
    }


    /**
    @OA\Delete(
        path = "/api/v1/coding-challenges/{codingChallenge}/",
        operationId = "deleteCodingChallenge",
        tags = {"CodingChallenge"},
        summary = "Delete a conding challenge",
        description = "Delete a conding challenge (soft delete)",
        @OA\Parameter(
            name = "codingChallenge",
            description = "Coding challenge key",
            required = true,
            in = "path",
            @OA\Schema(
                type = "string"
            )
        ),
        @OA\Response(
            response = "201",
            description = "Challenge updated",
            @OA\JsonContent(
                @OA\Property(
                    description = "Operation status",
                    property = "success",
                    example = true,
                    type = "boolean"
                ),
                @OA\Property(
                    description = "Operation message",
                    property = "message",
                    type="string",
                    example = "Challenge deleted",
                ),
                @OA\Property(
                    description = "Operation data (empty)",
                    property = "data",
                    example = "[]"
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
    public function destroy(CodingChallenge $coding_challenge)
    {
        if ($coding_challenge) {
            $coding_challenge->delete();

            $payload   = new ResponseSuccessResource([], "Challenge deleted");

            return response($payload, Response::HTTP_OK);
        }
    }


    public static function getPagination($data)
    {
        $data = $data->toArray();
        unset($data["data"]);
        $meta = $data;
        return $meta;
    }
}
