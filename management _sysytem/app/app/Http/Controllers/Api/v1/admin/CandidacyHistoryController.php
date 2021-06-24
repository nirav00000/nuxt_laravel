<?php

namespace App\Http\Controllers\Api\v1\admin;

use App\Candidacy;
use App\CandidacyHistory;
use App\Http\Controllers\Controller;
use App\Http\Resources\CandidacyHistoryResource;
use App\Http\Resources\ResponseSuccessResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class CandidacyHistoryController extends Controller
{
    /**
     * Get histories of a candidacy
     * @param Candidacy $candidacy
     @OA\Get(
        path = "/api/v1/admin/candidacy_histories/{candidacy}",
        operationId="getListOfHistoriesForACandidacy",
        tags = {"AdminCandidacyHistory"},
        summary = "Get All histories data of a candidacy",
        description = "Returns all history data of a candidacy",
        @OA\Parameter(
            name = "candidacy",
            description = "Candidacy key",
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
                    default = "Histories retrieved successfully",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data of Histories",
                    @OA\Property(
                        property="histories",
                        @OA\Items(
                            @OA\Property(property="key",ref="#/components/schemas/CandidacyHistory/properties/key",),
                            @OA\Property(property="actor",ref="#/components/schemas/CandidacyHistory/properties/actor",),
                            @OA\Property(property="stage_name",ref="#/components/schemas/CandidacyHistory/properties/stage_name",),
                            @OA\Property(property="status",ref="#/components/schemas/CandidacyHistory/properties/status",),
                            @OA\Property(property="metadata",type="object",example= {"duration": "2hour","interviewDate": "2021-03-24"},),
                            @OA\Property(property="created_at",ref="#/components/schemas/CandidacyHistory/properties/created_at",),
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
    public function index(Candidacy $candidacy)
    {
        $histories = CandidacyHistory::where('candidacy_id', $candidacy->id)->orderBy("created_at", "desc")->get();
        $response = new ResponseSuccessResource(
            ["histories" => CandidacyHistoryResource::collection($histories)],
            "Histories retrieved successfully"
        );

        return response($response, Response::HTTP_OK);
    }
}
