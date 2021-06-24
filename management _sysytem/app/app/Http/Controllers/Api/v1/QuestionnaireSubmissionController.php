<?php

namespace App\Http\Controllers\Api\v1;

use App\Candidacy;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionnaireSubmissionResource;
use App\Http\Resources\ResponseSuccessResource;
use App\Questionnaire;
use App\QuestionnaireSubmission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuestionnaireSubmissionController extends Controller
{
    /**
     * Show API for questionnaire_submission
     @OA\Get(
        path = "/api/v1/questionnaire_submissions/{questionnaire_submission}",
        operationId = "getOneQuestionnaireSubmissionData",
        tags = {"QuestionnaireSubmission"},
        summary = "Get data of one QuestionnaireSubmission.",
        description = "Returns one QuestionnaireSubmission.",
        @OA\Parameter(
            name = "questionnaire_submission",
            description = "QuestionnaireSubmission key",
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
                    default = "Questionnaire Submission retrieved succesfully",
                ),
                @OA\Property(
                    property = "data",
                    description = "Array of data of questionnaire_submission",
                    ref="#/components/schemas/QuestionnaireSubmission",
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
            @OA\JsonContent(ref="#/components/schemas/response4xx"),
        ),
    ),
    */
    public function show(QuestionnaireSubmission $questionnaireSubmission)
    {
        $response = new ResponseSuccessResource(new QuestionnaireSubmissionResource($questionnaireSubmission), "Questionnaire Submission retrieved succesfully");

        return response($response, Response::HTTP_OK);
    }


    /**
    @OA\Post(
        path = "/api/v1/questionnaire_submissions/{questionnaire}",
        operationId = "storeQuestionnaireSubmission",
        tags = {"QuestionnaireSubmission"},
        summary = "Inserts data of questionnaire submission to database",
        @OA\Parameter(
              name = "questionnaire",
              description = "Key of questionnaire",
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
                    @OA\Property(
                        property = "email",
                        description = "Email of candidate",
                        example = "haresh@gmail.com",
                    ),
                    @OA\Property(
                        property = "data",
                        description = "Array of question-answer pairs",
                        @OA\Items(
                            example = {"question":"Tell me something about yourself.", "answer":"I am an Computer Engineer with curiosity to explore tech" },
                        ),
                    ),
                ),
            ),
        ),
        @OA\Response(
            response = 201,
            description = "Sucessfull insertion",
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
                    default = "Questionnaire Answers Submitted Successfully",
                ),
                @OA\Property(
                    property = "data",
                    description = "Empty array",
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
    public function store(Questionnaire $questionnaire, Request $request)
    {
        $request->validate([
            'email' => 'required',
            'data' => 'required'
        ]);


        //getting candidacy based on email
        $candidacy = Candidacy::whereHas('candidate', function (Builder $query) use ($request) {
            $query->where('email', '=', $request->email);
        })->where('final_status', '=', 'active')->first();
        if (!$candidacy) {
            Log::error("Questionnaire Submitted for inactive or non-existing candidacy. Email = " . $request->email);
            return response(["success" => false, "message" => "Candidacy is either not exists or not active", "error" => []], Response::HTTP_BAD_REQUEST);
        }


        //make a submission
        $submission_metadata = [];
        foreach ($request->data as $record) {
            array_push($submission_metadata, $record);
        }


        //*1* submission new entry
        $submission = new QuestionnaireSubmission([
            "questionnaire_id" => $questionnaire->id,
            "candidacy_id" => $candidacy->id,
            "metadata" => $submission_metadata,
        ]);
        $submission->save();

        //*2* history new entry   *3* update candidacy metadata ... will be done in QuestionnaireSubmissionObserver


        $response  = new ResponseSuccessResource([], "Questionnaire Answers Submitted Successfully");
        return response($response, Response::HTTP_CREATED);
    }
}
