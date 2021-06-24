<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Stage;
use Illuminate\Http\Response;
use App\Http\Resources\ResponseSuccessResource;
use App\Http\Resources\StageResource;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;

class StageController extends Controller
{

    // list all stages


    /**
         @OA\Get(
            path = "/api/v1/stages",
            operationId = "getAllStagesData",
            tags = {"Stage"},
            summary = "Get list of stages.",
            description = "Returns list of stages.",
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
                        default = "Data retrived successfully.",
                    ),
                    @OA\Property(
                        property = "data",
                        description = "Array of data of all stages",
                        @OA\Property(
                            property = "stages",
                            type = "array",
                            @OA\Items(
                                ref="#/components/schemas/Stage",
                            ),
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
        ),
     */
    public function index()
    {
        $response  = new ResponseSuccessResource(
            [
                'stages' => StageResource::collection(Stage::all()),
            ],
            "Stages retrieved successfully"
        );
        return response($response, Response::HTTP_OK);
    }


    // find a stage

    /**
        @OA\Get(
            path = "/api/v1/stages/{stage}",
            operationId = "getStageData",
            tags = {"Stage"},
            summary = "Get a stage data using key",
            description = "returns stage data",
            @OA\Parameter(
                name = "stage",
                description = "Stage key",
                required = true,
                in = "path",
                @OA\Schema(
                    type = "string",
                ),
            ),
            @OA\Response(
                response = 200,
                description = "Stage retrieved successfully",
                @OA\JsonContent(
                    @OA\Property(
                        property = "success",
                        description = "Status of operation",
                        default = true,
                    ),
                    @OA\Property(
                        property = "message",
                        description = "Message for status of operation",
                        default = "Stage retrieved successfully.",
                    ),
                    @OA\Property(
                        property = "data",
                        description = "Array of data for requested stage",
                        @OA\Property(
                            property = "stage",
                            ref="#/components/schemas/Stage",
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
        ),

     */
    public function show(Stage $stage)
    {
        $response  = new ResponseSuccessResource(
            [
                'stage' => new StageResource($stage),
            ],
            "Stage retrieved successfully."
        );
        return response($response, Response::HTTP_OK);
    }


    // insert a new stage


    /**
    @OA\Post(
        path = "/api/v1/stages",
        operationId = "addStageData",
        tags = {"Stage"},
        summary = "Adds new stage data",
        description = "Add a new stage data",
        @OA\RequestBody(
            required = true,
            @OA\MediaType(
                mediaType = "application/json",
                @OA\Schema(
                    @OA\Property(property="name",ref="#/components/schemas/Stage/properties/name"),
                    @OA\Property(property = "type",ref="#/components/schemas/Stage/properties/type"),
                    @OA\Property(property = "metadata",ref="#/components/schemas/Stage/properties/metadata"),
                ),
            ),
        ),
        @OA\Response(
            response = 201,
            description = "Stage created successfully.",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    description = "Shows status of operation",
                    type = "boolean",
                    default = true,
                ),
                @OA\Property(
                    property = "message",
                    description = "Shows message for operation status ",
                    type = "string",
                    default = "Stage created successfully.",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data for requested stage",
                    @OA\Property(
                        property = "stage",
                        ref="#/components/schemas/Stage",
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
    ),
     */
    public function store(Request $request)
    {

        $request->validate(
            [
                'name' => 'required',
                'type' => 'required |in:questionnaire,interview,code',
            ]
        );

        $stage           = new Stage();
        $stage->name     = $request->name;
        $stage->type     = $request->type;
        $stage->metadata = $request->metadata ?? [];
        if ($stage->type == "questionnaire") {
            $request->validate(['questionnaire_key' => 'required|exists:questionnaires,key']);
            $stage->metadata = array_merge($stage->metadata, ["questionnaire_key" => $request->questionnaire_key]);
        }
        $stage->save();

        $response  = new ResponseSuccessResource(
            [
                'stage' => new StageResource($stage),
            ],
            "Stage created successfully.",
        );

        return response($response, Response::HTTP_CREATED);
    }


    // update a stage


    /**
    @OA\Put(
        path = "/api/v1/stages/{stage}",
        operationId = "updateStageRecord",
        tags = {"Stage"},
        summary = "Updates existing stage record",
        description = "Updates existing stage record and returns updated stage data",
        @OA\Parameter(
            name = "stage",
            description = "Key of Stage",
            required = true,
            in = "path",
            @OA\Schema(
                type = "string",
            ),
        ),
        @OA\RequestBody(
            required = true,
            @OA\MediaType(
                mediaType = "application/json",
                @OA\Schema(
                    @OA\Property(property="name",ref="#/components/schemas/Stage/properties/name"),
                    @OA\Property(property = "type",ref="#/components/schemas/Stage/properties/type"),
                    @OA\Property(property = "metadata",ref="#/components/schemas/Stage/properties/metadata"),
                ),
            ),
        ),
        @OA\Response(
            response = 200,
            description = "Stage updated successfully.",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    description = "Shows status of operation",
                    type = "boolean",
                    default = true,
                ),
                @OA\Property(
                    property = "message",
                    description = "Shows message for operation status ",
                    type = "string",
                    default = "Stage updated successfully.",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data for requested stage",
                    @OA\Property(
                        property = "stage",
                        ref="#/components/schemas/Stage",
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
    ),

     */
    public function update(Request $request, Stage $stage)
    {

        $request->validate(
            [
                'name' => 'required',
                'type' => 'required|in:questionnaire,interview,code',
            ]
        );

        $stage->name     = $request->name;
        $stage->type     = $request->type;
        $stage->metadata = $request->metadata ?? [];
        if ($stage->type == "questionnaire") {
            $request->validate(['questionnaire_key' => 'required|exists:questionnaires,key']);
            $stage->metadata = array_merge($stage->metadata, ["questionnaire_key" => $request->questionnaire_key]);
        }
        $stage->save();

        $response = new ResponseSuccessResource(
            [
                'stage' => new StageResource($stage),
            ],
            "Stage updated successfully."
        );

        return response($response, Response::HTTP_OK);
    }


    // delete a stage


    /**
    @OA\Delete(
        path = "/api/v1/stages/{stage}",
        operationId = "deleteStageRecord",
        tags = {"Stage"},
        summary = "Deletes existing stage record",
        description = "Deletes existing stage record and returns nothing",
        @OA\Parameter(
            name = "stage",
            description = "Key of Stage",
            required = true,
            in = "path",
            @OA\Schema(
                type = "string",
            ),
        ),
        @OA\Response(
            response = 200,
            description = "Stage deleted successfully",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    description = "Status of operation",
                    default = true,
                ),
                @OA\Property(
                    property = "message",
                    description = "Message for status of operation",
                    default = "Stage deleted successfully.",
                ),
                @OA\Property(
                    property = "data",
                    example = "[]"
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
    ),

    */
    public function destroy(Stage $stage)
    {
        $stage->delete();
        $response = new ResponseSuccessResource([], "Stage deleted successfully.");
        return response($response, Response::HTTP_OK);
    }
}
