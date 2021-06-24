<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Candidate;
use App\Http\Resources\ResponseSuccessResource;
use App\Http\Resources\CandidateResource;

class CandidateController extends Controller
{


                /**lists all candidates*/


    /**
    @OA\Get(
        path = "/api/v1/candidates",
        operationId = "getCandidatesList",
        tags = {"Candidate"},
        summary = "Get list of candidates",
        description = "Returns list of candidates",
        @OA\Response(
            response = 200,
            description = "Successful operation",
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
                    default = "Data retrieved successfully.",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data of all candidates",
                    type = "array",
                    @OA\Items(
                        type="object",
                        ref="#/components/schemas/Candidate",
                    ),
                ),
            ),
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
    public function index()
    {
        $response  = new ResponseSuccessResource(
            [
                'candidates' => CandidateResource::collection(Candidate::all()),
            ],
            "Data retrieved successfully."
        );
        $http_code = Response::HTTP_OK;

        return response($response, $http_code);
    }


                // gives a candidate if exists


    /**
    @OA\Get(
        path = "/api/v1/candidates/{candidate}",
        operationId = "getCandidateById",
        tags = {"Candidate"},
        summary = "Get candidate infromation",
        description = "Returns candidate data",
        @OA\Parameter(
            name = "candidate",
            description = "Candidate key",
            required = true,
            in = "path",
            @OA\Schema(
                type = "string"
            )
        ),
        @OA\Response(
            response = 200,
            description = "Successful Fetch",
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
                    default = "Data retrieved successfully.",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data of required candidate.",
                    @OA\Property(
                        property="candidate",
                        type="object",
                        ref="#/components/schemas/Candidate",
                    ),
                ),
            ),
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
    public function show(Candidate $candidate)
    {
        $response  = new ResponseSuccessResource(
            [
                'candidate' => new CandidateResource($candidate),
            ],
            "Data retrieved successfully."
        );
        $http_code = Response::HTTP_OK;

        return response($response, $http_code);
    }


                // insert a record


    /**
    @OA\Post(
         path = "/candidates",
         operationId = "storeCandidate",
         tags = {"Candidate"},
         summary = "Store a new candidate",
         description = "Creates a new candidate and returns that candidate's data",
         @OA\RequestBody(
             required = true,
            @OA\MediaType(
                mediaType="application/json",
                @OA\Schema(
                    @OA\Property(property="name",ref="#/components/schemas/Candidate/properties/name",),
                    @OA\Property(property="email",ref="#/components/schemas/Candidate/properties/email",),
                    @OA\Property(property="metadata",ref="#/components/schemas/Candidate/properties/metadata",),
                ),
            ),
         ),
        @OA\Response(
            response = 201,
            description = "Successful Creation",
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
                    default = "Record created successfully.",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data of created candidate",
                    @OA\Property(
                        property="candidate",
                        type="object",
                        ref="#/components/schemas/Candidate",
                    ),
                ),
            ),
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
    public function store(Request $request)
    {
        $request->validate(
            [
                'name'  => 'required',
                'email' => 'required|email',
            ]
        );

        $candidate = new Candidate();
        // set only those which are provided
        $candidate->applyRequest($request);

        $response  = new ResponseSuccessResource(
            [
                'candidate' => new CandidateResource($candidate),
            ],
            "Record created successfully.",
        );
        $http_code = Response::HTTP_CREATED;

        return response($response, $http_code);
    }


            // update a record


    /**
    @OA\Put(
        path = "/api/v1/candidates/{candidate}",
        operationId = "updateCandidate",
        tags = {"Candidate"},
        summary = "Update existing Candidate",
        description = "Updates candidate and returns updated data",
        @OA\Parameter(
            name = "candidate",
            description = "Candidate key",
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
                    @OA\Property(property="name",ref="#/components/schemas/Candidate/properties/name",),
                    @OA\Property(property="email",ref="#/components/schemas/Candidate/properties/email",),
                    @OA\Property(property="metadata",ref="#/components/schemas/Candidate/properties/metadata",),
                ),
            ),
         ),
        @OA\Response(
            response = 200,
            description = "Successful Updation",
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
                    default = "Candidate data updated successfully.",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data of updated candidate.",
                    @OA\Property(
                        property="candidate",
                        type="object",
                        ref="#/components/schemas/Candidate",
                    ),
                ),
            ),
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
    public function update(Request $request, Candidate $candidate)
    {
        $request->validate(
            [
                'name'  => 'required',
                'email' => 'required|email',
            ]
        );
        ;
        // set only those which are provided
        $candidate->applyRequest($request);

        $response = new ResponseSuccessResource(
            [
                'candidate' => new CandidateResource($candidate),
            ],
            "Candidate data updated successfully."
        );

        $http_code = Response::HTTP_OK;

        return response($response, $http_code);
    }


            // deletes a candidate


    /**
    @OA\Delete(
        path = "/api/v1/candidates/{candidate}",
        operationId = "deleteCandidate",
        tags = {"Candidate"},
        summary = "Delete existing Candidate",
        description = "Deletes a candidate",
        @OA\Parameter(
                name = "candidate",
                description = "Candidate key",
                required = true,
                in = "path",
                @OA\Schema(
                type = "string"
                )
        ),
        @OA\Response(
            response = 200,
            description = "Successful deletion",
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
                    default = "Candidate deleted successfully.",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data of deleted candidate",
                    @OA\Property(
                        property="candidate",
                        type="object",
                        ref="#/components/schemas/Candidate",
                    ),
                ),
            ),
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
    public function destroy(Candidate $candidate)
    {
        $response = new ResponseSuccessResource(
            [
                'candidate' => new CandidateResource($candidate),
            ],
            "Candidate deleted successfully."
        );
        $candidate->delete();

        $http_code = Response::HTTP_OK;

        return response($response, $http_code);
    }
}
