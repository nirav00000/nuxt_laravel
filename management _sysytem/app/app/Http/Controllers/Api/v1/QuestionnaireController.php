<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionnaireResource;
use App\Http\Resources\ResponseSuccessResource;
use App\Http\Resources\ResponseFailureResource;
use App\Questionnaire;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuestionnaireController extends Controller
{
    /**
     * get list of all questionnaire
    @OA\Get(
        path = "/api/v1/questionnaires",
        operationId = "getAllQuestionnaires",
        tags = {"Questionnaire"},
        summary = "Get list of questionnaires.",
        description = "Returns list of questionnaires.",
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
                    default = "questionnaires retreived successfully",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data of all questionnaires",
                        @OA\Items(
                                type="object",
                                ref="#/components/schemas/Questionnaire",
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
        $response = new ResponseSuccessResource(
            QuestionnaireResource::collection(Questionnaire::all()),
            "Questionnaires retreived successfully"
        );

        return response($response, Response::HTTP_OK);
    }

    // Note: this is temporary route
    public function seedQuestionnaire(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                'url' => 'required',
            ]
        );

        $questionnaire = new Questionnaire();
        $questionnaire->name = $request->name;
        $questionnaire->metadata = ["url" => $request->url];
        $questionnaire->save();

        $response = new ResponseSuccessResource(
            QuestionnaireResource::collection(Questionnaire::all()),
            "Questionnaire created successfully"
        );

        return response($response, Response::HTTP_CREATED);
    }

    /**
    @OA\Post(
        path = "/api/v1/questionnaires",
        operationId = "addQuestionnaire",
        tags = {"Questionnaire"},
        summary = "Add new Questionnaire ",
        description = "Add a new Questionnaire data",
        @OA\RequestBody(
            required = true,
            @OA\MediaType(
                mediaType = "application/json",
                @OA\Schema(
                    @OA\Property(property="name",ref="#/components/schemas/Questionnaire/properties/name"),
                ),
            ),
        ),
        @OA\Response(
            response = 201,
            description = "Questionnaire created successfully.",
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
                    default = "Questionnaire created successfully.",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data for requested Questionnaire",
                    @OA\Items(
                            type="object",
                            ref="#/components/schemas/Questionnaire",
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
        return $this->saveQuestionnaire(
            new Questionnaire(),
            $request,
            "Questionnaire created successfully.",
            Response::HTTP_CREATED
        );
    }
    /**
     * get Questionnaire
        @OA\Get(
            path = "/api/v1/questionnaires/{questionnaire}",
            operationId = "getQuestionnaire",
            tags = {"Questionnaire"},
            summary = "Get a Questionnaire by key",
            description = "returns Questionnaire data",
            @OA\Parameter(
                name = "questionnaire",
                description = "Questionnaire key",
                required = true,
                in = "path",
                @OA\Schema(
                    type = "string",
                ),
            ),
            @OA\Response(
                response = 200,
                description = "Questionnaire retrieved successfully",
                @OA\JsonContent(
                    @OA\Property(
                        property = "success",
                        description = "Status of operation",
                        default = true,
                    ),
                    @OA\Property(
                        property = "message",
                        description = "Message for status of operation",
                        default = "Questionnaire retrieved successfully.",
                    ),
                    @OA\Property(
                        property = "data",
                        description = "Array of data for requested questionnaire",
                        @OA\Items(
                                type="object",
                                ref="#/components/schemas/Questionnaire",
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


    public function show(Questionnaire $questionnaire)
    {
        return response(
            new QuestionnaireResource($questionnaire),
            Response::HTTP_OK
        );
    }

    /**
    @OA\Put(
        path = "/api/v1/questionnaires/{questionnaire}",
        operationId = "updateQuestionnaire",
        tags = {"Questionnaire"},
        summary = "Updates existing Questionnaire",
        description = "Updates existing questionnaire record and returns updated questionnaire data",
        @OA\Parameter(
            name = "questionnaire",
            description = "Questionnaire Key",
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
                    @OA\Property(property="name",ref="#/components/schemas/Questionnaire/properties/name"),
                ),
            ),
        ),
        @OA\Response(
            response = 200,
            description = "Questionnaire updated successfully.",
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
                    default = "Questionnaire updated successfully.",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data for requested Questionnaire",
                    @OA\Items(
                            type="object",
                            ref="#/components/schemas/Questionnaire",
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

    public function update(Questionnaire $questionnaire, Request $request)
    {
        return $this->saveQuestionnaire(
            $questionnaire,
            $request,
            "Questionnaire updated successfully.",
            Response::HTTP_OK
        );
    }

    /**
    @OA\Delete(
        path = "/api/v1/questionnaires/{questionnaire}",
        operationId = "deleteQuestionnaire",
        tags = {"Questionnaire"},
        summary = "Deletes existing questionnaire",
        description = "Deletes existing questionnaire",
        @OA\Parameter(
            name = "questionnaire",
            description = "Key of Questionnaire",
            required = true,
            in = "path",
            @OA\Schema(
                type = "string",
            ),
        ),
        @OA\Response(
            response = 200,
            description = "Questionnaire deleted successfully",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    description = "Status of operation",
                    default = true,
                ),
                @OA\Property(
                    property = "message",
                    description = "Message for status of operation",
                    default = "Questionnaire deleted successfully.",
                ),
                @OA\Property(
                    property = "data",
                    @OA\Items(),
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

    public function destroy(Questionnaire $questionnaire, Request $request)
    {
        $questionnaire->delete();
        return response(
            new ResponseSuccessResource([], "Questionnaire deleted."),
            Response::HTTP_OK
        );
    }

    protected function saveQuestionnaire(
        Questionnaire $questionnaire,
        Request $request,
        string $message,
        $code
    ) {
        $validation = Validator::make($request->all(), [
            "name" => "required"
        ]);
        if ($validation->fails()) {
            return response(
                new ResponseFailureResource(null, $validation->errors()->first()),
                Response::HTTP_BAD_REQUEST
            );
        }
        $questionnaire->name = $request->name;
        if ($request->has('metadata')) {
            $questionnaire->metadata = $request->metadata;
        }
        $questionnaire->save();
        $response = new ResponseSuccessResource(
            new QuestionnaireResource($questionnaire),
            $message
        );
        return response($response, $code);
    }
}
