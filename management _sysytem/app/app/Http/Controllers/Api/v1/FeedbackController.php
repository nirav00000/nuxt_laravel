<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Feedback;
use App\Candidacy;
use App\CandidacyHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Filters\ApiFilter;
use App\Helpers\Helper;
use Illuminate\Validation\Rule;
use App\Http\Resources\ResponseSuccessResource;
use App\Http\Resources\FeedbackResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{

    //get all feedback data



    /**
     * @OA\Get(
     *      path="/api/v1/candidacies/{candidacy}/feedback",
     *      operationId="getFeedbackList",
     *      tags={"Feedback"},
     *      summary="Get feedback of candidacy",
     *      description="Feedback list of candidacy",
     *
     *
     *  @OA\Response(
     *       response = 200,
     *       description = "Sucessfull Retrieval",
     *       @OA\JsonContent(
     *           @OA\Property(
     *               property = "success",
     *               description = "Shows operation status",
     *               type = "boolean",
     *               default = true,
     *           ),
     *           @OA\Property(
     *               property = "message",
     *               description = "data retrived successfully",
     *               example = "data retrived successfully",
     *               type = "string",
     *
     *           ),
     *
     *           @OA\Property(
     *               property = "data",
     *               description = "collection of array",
     *               @OA\Items(
     *                  type="object",
     *                  ref="#/components/schemas/Feedback",
     *                  ),
     *
     *           ),
     *
     *      ),
     *   ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found "
     *      )
     *  )
     */




    public function index(Request $request)
    {
        $this->validate($request, [
            'feedback' => Rule::in(['Yes', 'No', 'Maybe']),
            'user_id' => 'exists:users,id',
        ]);

        $filter = new ApiFilter(new Feedback(), $request);
        $filter->apply(config('filters.feedback'));
        $feedbackData = $filter->getData();
        $paginationData  = Helper::getPaginationData($feedbackData);

        $response = new ResponseSuccessResource(
            [
               'feedback' => FeedbackResource::collection($feedbackData),
               'meta' => $paginationData
            ],
            "Feedback retrived successfully."
        );
        return response($response, Response::HTTP_OK);
    }





    /**
     * @OA\Get(
     *      path="/api/v1/feedback/{feedback}",
     *      operationId="getFeedbackByKey",
     *      tags={"Feedback"},
     *      summary="Get information of Feedback",
     *      description=" information of Feedback",
     *
     *
     *  @OA\Response(
     *       response = 200,
     *       description = "Sucessfull Retrieval",
     *       @OA\JsonContent(
     *           @OA\Property(
     *               property = "success",
     *               description = "Shows operation status",
     *               type = "boolean",
     *               default = true,
     *           ),
     *           @OA\Property(
     *               property = "message",
     *               description = "Feedback retrived successfully",
     *               example = "Feedback retrived successfully",
     *               type = "string",
     *
     *           ),
     *
     *           @OA\Property(
     *               property = "data",
     *               description = "collection of array",
     *               @OA\Items(
     *                  type="object",
     *                  ref="#/components/schemas/Feedback",
     *
     *               ),
     *           ),
     *
     *      ),
     *   ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found "
     *      )
     *  )
     */

    //get feedback data of given key

    public function show(Feedback $feedback)
    {
        try {
            $feedback = new FeedbackResource($feedback);

            $response = [
                'success'   => true,
                'message'   => 'Feedback retrieved Successfully',
                'data'      =>  $feedback,
            ];

            return $response;
        } catch (\Exception $e) {
            $response = [
                'success'   => false,
                'message'   => 'Something went wrong.',
                'error'     => $e->getMessage()
            ];

            return $response;
        }
    }

    /**
    @OA\Post(
        path="/api/v1/candidacies/{candidacy}/feedback",
        operationId="storeFeedback",
        tags={"Feedback"},
        summary="Store new Feedback",
        description="Create New Feedback",
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
                    @OA\Property(
                        property="verdict",
                        type="string",
                        example="yes"
                    ),
                    @OA\Property(
                        property="description",
                        type="string",
                        example="He was good at communication skill and technical concepts were clear."
                    ),
                ),
            ),
        ),
           @OA\Response(
               response=201,
               description="Successful operation",
               @OA\JsonContent(
               @OA\Property(
                    property = "success",
                    description = "Shows operation status",
                    type = "boolean",
                    default = true,
                ),
                @OA\Property(
                    property = "message",
                    type = "string",
                    description = "Feedback created successfully",
                    example = "Feedback Created Successfully",
                ),
                @OA\Property(
                    property="data",
                    description="Use to indentify record insted of id,  Mustbe in 6 latters",
                    type="string",
                    example="xYsw03"
                )
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

    //save feedback data

    public function store(Request $request, Candidacy $candidacy)
    {
        $request->validate([
            'verdict' => 'required | in:yes,no,maybe',
            'description' => 'required',
        ]);

        $actor = Auth::user();
        //for feedback
        $feedback               = new Feedback();
        $feedback->verdict      = $request->verdict;
        $feedback->description  = $request->description;
        $feedback->user_id      = $actor->id;
        $feedback->candidacy_id = $candidacy->id;
        $feedback->save();

        //for history
        $default = [
            "candidacy_id" => $candidacy->id,
            "actor" => $actor->name,
            "stage_name" => ($request->stage_name) ? Str::snake($request->stage_name) : ""
        ];
        $extra = [
            "metadata" => [
                "actor_key" => $actor->key,
                "feedback_key" => $feedback->key,
                "verdict" => $request->verdict
            ]
        ];
        CandidacyHistory::createFromData($default, $request, ["metadata"], $extra);

        // Update candidacy metadata
        // $metadata = $candidacy['metadata'];

        // if (!isset($metadata['feedbacks'])) {
        //     $metadata['feedbacks'] = [];
        // }

        // $feedback_for_candidacy = ["actor" =>  $actor->id, "verdict" => $request->verdict];

        // array_push($metadata['feedbacks'], $feedback_for_candidacy);

        // //update metadata
        // $candidacy->update(['metadata' => $metadata]);


        $response  = new ResponseSuccessResource($feedback->key, "Feedback Created Successfully");
        return response($response, Response::HTTP_CREATED);
    }


    /**
     * @OA\Put(
     *      path="/api/v1/feedback/{feedback}",
     *      operationId="updateFeedback",
     *      tags={"Feedback"},
     *      summary="Update existing Feedback",
     *      description="Returns updated feedback data",
     *      @OA\Parameter(
     *          name="key",
     *          description="Feedback key",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *
     *  @OA\Response(
     *       response = 200,
     *       description = "Sucessfull Retrieval",
     *       @OA\JsonContent(
     *           @OA\Property(
     *               property = "success",
     *               description = "Shows operation status",
     *               type = "boolean",
     *               default = true,
     *           ),
     *           @OA\Property(
     *               property = "message",
     *               description = "data updated successfully",
     *               example = "data updated successfully",
     *               type = "string",
     *
     *           ),
     *
     *           @OA\Property(
     *               property = "data",
     *               description = "collection of array",
     *               @OA\Items(
     *                  type="object",
     *                  ref="#/components/schemas/Feedback",
     *
     *               ),
     *           ),
     *
     *      ),
     *   ),
     *
     *      @OA\Response(
     *          response=422,
     *          description="Unproccessable Entity"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */

    //update feedback data of given key

    public function update(Request $request, Feedback $feedback)
    {


        $request->validate([

            'feedback'      => Rule::in(['Yes', 'No', 'Maybe']),
            'description'   => 'required',
            'user_id'       => 'required|exists:users,id',

        ]);


        try {
            $feedback->verdict     = $request->verdict;
            $feedback->description  = $request->description;
            $feedback->user_id      = $request->user_id;
            $feedback->candidacy_id      = $request->candidacy_id;

            //dd($feedback);

            $feedback->save();
            //exit;

            $feedback = new FeedbackResource($feedback);

            $response = [
                'success'   => true,
                'message'   => 'Feedback Updated Successfully',
                'data'      =>  $feedback,
            ];


            return $response;
        } catch (\Exception $e) {
            $response = [
                'success'   => false,
                'message'   => 'Something went wrong.',
                'error'     => $e->getMessage()
            ];

            return $response;
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/feedback/{feedback}",
     *      operationId="deleteFeedback",
     *      tags={"Feedback"},
     *      summary="Delete existing Feedback",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="key",
     *          description="Feedback key",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */

    //delete feedback data of given key

    public function delete(Feedback $feedback)
    {


        try {
            $feedback->delete();

            $response = [
                'success'   => true,
                'message'   => 'Data Deleted Successfully',
            ];

            $http_code = Response::HTTP_ACCEPTED;

            return response($response, $http_code);
        } catch (\Exception $e) {
            $response = [
                'success'   => false,
                'message'   => 'Something went wrong.',
                'error'     => $e->getMessage()
            ];

            return $response;
        }
    }
}
