<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ResponseSuccessResource;
use App\Http\Resources\CandidacyResource;
use App\Candidate;
use App\Candidacy;
use App\CandidacyHistory;
use App\CodingChallenge;
use App\CodingSession;
use App\Filters\ApiFilter;
use App\Helpers\Helper;
use App\Http\Resources\ResponseFailureResource;
use App\Questionnaire;
use App\Stage;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Events\StageAssigned;
use App\Events\CloseStage;
use App\Events\CloseCandidacy;
use App\Events\CandidateRegistration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CandidacyController extends Controller
{





    /**
     * get list of all candidacie
    @OA\Get(
        path = "/api/v1/candidacies",
        operationId = "getAllCandidaciesData",
        tags = {"Candidacy"},
        summary = "Get list of candidacies.",
        description = "Returns list of candidacies.",
        @OA\Parameter(
            name = "final_status",
            description = "final_status of candidacy",
            required = false,
            in = "path",
            @OA\Schema(
                type = "string",
                example="active",
            ),
        ),
        @OA\Parameter(
            name = "candidate_name",
            description = "name of candidate",
            required = false,
            in = "path",
            @OA\Schema(
                type = "string",
                example="Rajiv Gandhi",
            ),
        ),
        @OA\Parameter(
            name = "position",
            description = "position of role",
            required = false,
            in = "path",
            @OA\Schema(
                type = "string",
                example="Software Engineer",
            ),
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
                    default = "Candidacies retreived successfully",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data of all candidacies",
                    @OA\Property(
                        property="candidacies",
                        @OA\Items(
                                type="object",
                                ref="#/components/schemas/Candidacy",
                            ),
                        ),
                        @OA\Property(
                            property = "meta",
                            ref="#/components/schemas/paginationMeta",
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
    public function index(Request $request)
    {
        $extras = [];
        if ($request->final_status === "all") {
             $request->final_status = null;
        } else if (empty($request->final_status)) {
            $extras = ["final_status" => "active"];
        }

        $actor = Auth::user();

        $query = Candidacy::whereNotNull('metadata->assignees->' . $actor->key);

        $filter = new ApiFilter($query, $request, $extras);
        $filter->apply(config('filters.candidacy'));
        $filter->applyAdvancedFilter(config('filters.candidacy_has'));
        $candidaciesData = $filter->getData();
        $paginationData  = Helper::getPaginationData($candidaciesData);

        $response = new ResponseSuccessResource(
            [
                "candidacies" => CandidacyResource::collection($candidaciesData),
                "meta"        => $paginationData,
            ],
            "Candidacies retreived successfully"
        );

        $http_code = Response::HTTP_OK;

        return response($response, $http_code);
    }


    /**
     * get one Candidacy
    @OA\Get(
        path = "/api/v1/candidacies/{candidacy}",
        operationId = "getOneCandidacyData",
        tags = {"Candidacy"},
        summary = "Get data of one candidacy.",
        description = "Returns one candidacy.",
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
                    default = "Candidacy retreived successfully",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data of candidacy",
                    ref="#/components/schemas/Candidacy",

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
    public function show(Candidacy $candidacy)
    {
        $response = new ResponseSuccessResource(new CandidacyResource($candidacy), "Candidacy retreived successfully");

        $http_code = Response::HTTP_OK;

        return response($response, $http_code);
    }


    /**
      swagger docs here
      API for creating candidacy when candidate gets registered

      @OA\Post(
        path = "/api/v1/candidacies",
        operationId = "createNewCandidacy",
        tags = {"Candidacy"},
        summary = "Creates a new candidacy",
        description = "Returns key of candidacy",
        @OA\RequestBody(
            required = true,
           @OA\MediaType(
               mediaType="application/json",
               @OA\Schema(
                   @OA\Property(property="name",ref="#/components/schemas/Candidate/properties/name",),
                   @OA\Property(property="email",ref="#/components/schemas/Candidate/properties/email"),
                   @OA\Property(property="position",ref="#/components/schemas/Candidacy/properties/position"),
                   @OA\Property(property="metadata",ref="#/components/schemas/Candidate/properties/metadata"),
               ),
           ),
        ),
        @OA\Response(
            response = 201,
            description = "Sucessfull Creation",
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
                    default = "Candidacy initiated successfully",
                ),
                @OA\Property(
                    property = "data",
                    description = "data of candidacy",
                    @OA\Property(
                        property = "candidacy_key",
                        description = "candidacy key to access it",
                        type = "string",
                        example = "UzMOt5LIEvzkfFSh",
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
                'name'     => 'required',
                'email'    => 'required|email',
                'position' => 'required',
            ]
        );

        $candidate = Candidate::where('email', $request->email)->first();

        if ($candidate) {
            Candidacy::where('candidate_id', $candidate->id)->update(['final_status' => 'inactive']);
        }

        if ($candidate === null) {
            $candidate = new Candidate();
        }

        // set only those which are provided
        $candidate->applyRequest($request);

        $candidacy = new Candidacy();
        $candidacy->candidate_id = $candidate->id;
        $candidacy->position     = $request->position;
        $candidacy->final_status = "active";
        $candidacy->save();

        event(new CandidateRegistration($candidate));

        $response  = new ResponseSuccessResource(
            [
                "candidacy_key" => $candidacy->key,
            ],
            'Candidacy initiated successfully'
        );
        $http_code = Response::HTTP_CREATED;

        return response($response, $http_code);
    }


    /**
    * API for closing stage
    @OA\Post(
        path = "/api/v1/candidacies/{candidacy}/closeStage",
        operationId = "closeStageOfCandidacy",
        tags = {"StageAssignment"},
        summary = "Closes stage of candidacy",
        description = "Returns success response if stage closed",
        @OA\Parameter(
              name = "candidacy",
              description = "Key of Candidacy",
              required = true,
              in = "path",
              @OA\Schema(
                  type = "string",
              ),
        ),
        @OA\RequestBody(
            required = true,
            @OA\MediaType(
                mediaType="application/json",
                @OA\Schema(
                    @OA\Property(property="stage_name",ref="#/components/schemas/CandidacyHistory/properties/stage_name"),
                    @OA\Property(property="stage_closing_reason", type="string", example="Completed the stage",),
                ),
            ),
        ),
        @OA\Response(
          response = 200,
          description = "Closing Successfull",
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
                  default = "Stage closed successfully.",
              ),
              @OA\Property(
                  property = "data",
                  example = {},
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
    public function closeStage(Candidacy $candidacy, Request $request)
    {
        $request->validate([
          "stage_name" => "required",
        ]);

        $stageName = $request->stage_name;
        $stage = Stage::where('name', $stageName)->first();


        //error if inactive
        if ($candidacy->final_status === "inactive") {
            return response(["success" => false,"message" => "Candidacy is inactive", "error" => []], Response::HTTP_BAD_REQUEST);
        }

        //error if stage is not started
        if (isset($candidacy->metadata["stages"][Str::snake($request->stage_name)]) === false) {
            return response(["success" => false,"message" => "Stage is not created yet", "error" => []], Response::HTTP_BAD_REQUEST);
        }

        //set default reason
        if (isset($request->stage_closing_reason) === false) {
            $request->merge(["stage_closing_reason" => "Reason not provided."]);
        }

        //get logged in user
        $actor = Auth::user();

        // Check stage assigned to that actor
        if ($candidacy->metadata["stages"][Str::snake($request->stage_name)]["assignee_key"] !== $actor->key) {
            return response(["success" => false,"message" => "Stage is not assigned to you", "error" => []], Response::HTTP_BAD_REQUEST);
        }




        $default = [
            "candidacy_id" => $candidacy->id,
            "stage_name" => Str::snake($request->stage_name),
            "status" => "completed",
            "actor" => $actor->name
        ];
        $extra = [
            "metadata" => [
                "stage_closing_reason" => $request->stage_closing_reason,
                "actor_key" => $actor->key,
                "assignee_key" => $candidacy->metadata["stages"][Str::snake($request->stage_name)]["assignee_key"]
            ]
        ];
        CandidacyHistory::createFromData($default, $request, ["metadata"], $extra);

        //change candidacy metadata
        $candidacy->updateStageMetadata();


        event(new CloseStage($history));


        $response  = new ResponseSuccessResource([], "Stage closed successfully.");
        $http_code = Response::HTTP_OK;

        return response($response, $http_code);
    }
}
