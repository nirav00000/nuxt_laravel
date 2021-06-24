<?php

namespace App\Http\Controllers\Api\v1;

use App\Candidacy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\CandidacyHistory;
use App\Candidate;
use App\CodingSession;
use App\CodingChallenge;
use App\CodingSubmission;
use Illuminate\Support\Facades\Artisan;

date_default_timezone_set("UTC");

class CodingSessionController extends Controller
{
    /**
    @OA\Post(
        path = "/api/v1/coding-sessions/fetch-session/?session_id=ID&start=true",
        operationId = "fetchCodingSession",
        tags = {"CodingSessions"},
        summary = "Fetch session or start session",
        description = "This API fetch coding session by session_id and start the session if start query params supplied",
        @OA\RequestBody(),
        @OA\Response(
            response = "200",
            description = "Session fetched",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    example = true,
                    type = "boolean",
                    description = "Operation status"
                ),
                @OA\Property(
                    property = "session_id",
                    type="string",
                    example = "ID",
                    description = "Session id"
                ),
                @OA\Property(
                    property = "is_valid",
                    type="boolean",
                    example = true,
                    description = "Return true if session is valid (session entry found in session table)."
                ),
                @OA\Property(
                    property = "is_started",
                    type="boolean",
                    example = true,
                    description = "Return true if session is started."
                ),
                @OA\Property(
                    property = "is_submitted",
                    type="boolean",
                    example = true,
                    description = "Return true if session is submitted."
                ),
                @OA\Property(
                    property = "is_expire",
                    type="boolean",
                    example = "true",
                    description = "Return true if session is expired."
                ),
                @OA\Property(
                    property = "data",
                    description = "Coding session data - Coding challenge",
                    @OA\Items(
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
                        @OA\Property(
                            property = "inputs",
                            description = "Problem inputs",
                            example = "Problem inputs"
                        ),
                        @OA\Property(
                            property = "output",
                            description = "Problem output",
                            example = "Problem output"
                        ),
                    )
                ),
                @OA\Property(
                    property = "playgrund",
                    description = "Last saved code and languages",
                    @OA\Items(
                        @OA\Property(
                            property = "code",
                            example = "function problem() {}",
                            description = "Last written code, and empty if newly created"
                        ),
                        @OA\Property(
                            property = "language",
                            description = "Last saved language",
                            example = "javascript"
                        ),
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
    public function fetchSession(Request $request)
    {
        // Get session id
        $session = $request->session_id;
        $startChellenge = $request->start;

        $sessionStarted = false;
        $session = CodingSession::where('key', $session)->first();

        // Remove after middleware fix
        if (!$session) {
            return response(array(
                'success' => false,
                'is_valid' => false,
                'message' => 'Invalid session'
            ), Response::HTTP_BAD_REQUEST);
        }


        $candidacyId = $session['candidacy_id'];

        $candidacy = Candidacy::where('id', $candidacyId)->first();
        $candidate = Candidate::where('id', $candidacy->candidate_id)->first();

        $stage = CandidacyHistory::where([
            'candidacy_id' => $candidacyId,
            'metadata->session_key' => $session->key,
            'status' => 'created'
        ])->get();

        // Check in history is coding session started
        $started = CandidacyHistory::where([
            'candidacy_id' => $candidacyId,
            'stage_name' =>  $stage[0]->stage_name,
            'status' => 'started'
        ])->get();

        // Stated challenge
        if ($session->started_at !== null) {
            $sessionStarted = true;
        } else {
            $sessionStarted = false;
        }


        // Candidacy not started coding challenge and not added request to start
        if (!$sessionStarted && !$startChellenge) {
            return response([
                'success' => true,
                'session_id' => $session['key'],
                'is_valid' => true,
                'is_started' => false,
                'is_submitted' => false,
                'is_expired' => false,
                'data' => array()
            ], Response::HTTP_OK);
        }

        // Not yet challenge started but not want to start challenge
        if (!$sessionStarted && $startChellenge) {
            // User requested to start challenge
            // Do entry in history
            $started = CandidacyHistory::create([
                'candidacy_id' => $candidacyId,
                'stage_name' =>  $stage[0]->stage_name,
                'actor' => $candidate->name,
                'status' => 'started',
                'metadata' => ['session_key' => $session->key, 'assignee_key' => $stage[0]["metadata"]["assignee_key"]]
            ]);

            $codingSession = CodingSession::where('key', $request->session_id)->get()->first();
            $codingSession->started_at = now('UTC');
            $codingSession->save();

            if ($started) {
                $sessionStarted = true;
            }
        }


        // If submitted
        $submission = CodingSubmission::where(['session_id' => $session->id])->first();
        if ($submission) {
            $challenge = CodingChallenge::where(['id' => $session->challenge_id])->get();
            $title = $challenge[0]->title;
            $description = $challenge[0]->description;
            $test = json_decode($challenge[0]->tests);
            $testInputs = $test[0]->inputs;
            $testOutput = $test[0]->output;
            $code = $submission['code'];
            $language = $submission['language'];

            // Fetch code
            return response([
                'success' => true,
                'session_id' => $session['key'],
                'is_valid' => true,
                'is_started' => true,
                'is_submitted' => true,
                'is_expired' => true,
                'data' => [
                    'title' => $title,
                    'description' => $description,
                    'inputs' => $testInputs,
                    'output' => $testOutput
                ],
                'playground' => [
                    'code' => $code,
                    'language' => $language
                ]
            ], Response::HTTP_OK);
        }

        // SESSION EXPIRED && NO SUBMISSION HAPPEND
        if ($sessionStarted && !(strtotime($session->started_at) > strtotime('-' . env('CODING_SESSION_EXPIRY_TIME', 30) . 'minutes'))) {
            // Not written in submission but session is expired
            $challenge = CodingChallenge::where(['id' => $session->challenge_id])->get();
            $title = $challenge[0]->title;
            $description = $challenge[0]->description;
            $test = json_decode($challenge[0]->tests);
            $testInputs = $test[0]->inputs;
            $testOutput = $test[0]->output;
            $code = $session['code'];
            $language = $session['language'];

            if (!$session['code']) {
                $code = '';
            }
            if (!$session['language']) {
                $language = '';
            }

            return response([
                'success' => true,
                'session_id' => $session['key'],
                'is_valid' => true,
                'is_started' => true,
                'is_submitted' => false,
                'is_expired' => true,
                'data' => [
                    'title' => $title,
                    'description' => $description,
                    'inputs' => $testInputs,
                    'output' => $testOutput
                ],
                'playground' => [
                    'code' => $code,
                    'language' => $language
                ]
            ], Response::HTTP_OK);
        } else {
            // NOT EXPIRED && NO SUBMISSION HAPPEN
            $challenge = CodingChallenge::where(['id' => $session->challenge_id])->get();
            $title = $challenge[0]->title;
            $description = $challenge[0]->description;
            $test = json_decode($challenge[0]->tests);
            $testInputs = $test[0]->inputs;
            $testOutput = $test[0]->output;
            $code = $session['code'];
            $language = $session['language'];

            if (!$session['code']) {
                $code = '';
            }
            if (!$session['language']) {
                $language = '';
            }

            // Fetch code
            return response([
                'success' => true,
                'session_id' => $session['key'],
                'is_valid' => true,
                'is_started' => true,
                'is_submitted' => false,
                'is_expired' => false,
                'data' => [
                    'title' => $title,
                    'description' => $description,
                    'inputs' => $testInputs,
                    'output' => $testOutput
                ],
                'playground' => [
                    'code' => $code,
                    'language' => $language
                ]
            ], Response::HTTP_OK);
        }
    }


    /**
        @OA\Post(
        path = "/api/v1/coding-sessions/run/?session_id=ID",
        operationId = "runCodingSession",
        tags = {"CodingSessions"},
        summary = "Run the code",
        description = "This API run the code and save code in session",
        @OA\RequestBody(
            required = true,
           @OA\MediaType(
               mediaType="application/json",
               @OA\Schema(
                   @OA\Property(description = "Code that we want to run", property="code", example= "function problem() {}", type="string"),
                   @OA\Property(description = "language of code we want to run", property="language", example= "javascript", type="string"),
                   @OA\Property(description = "STDIN", property="inputs", example= "hello", type="string"),
                   @OA\Property(description = "Program output should be ...", property="output", example= "hello", type="string"),
                ),
           ),
        ),
        @OA\Response(
            response = "200",
            description = "Code Runned",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    example = true,
                    type = "boolean",
                    description = "Operation status"
                ),
                @OA\Property(
                    property = "session_id",
                    type="string",
                    example = "ID",
                    description = "Session id"
                ),
                @OA\Property(
                    property = "is_valid",
                    type="boolean",
                    example = true,
                    description = "Return true if session is valid (session entry found in session table)."
                ),
                @OA\Property(
                    property = "is_started",
                    type="boolean",
                    example = true,
                    description = "Return true if session is started."
                ),
                @OA\Property(
                    property = "is_submitted",
                    type="boolean",
                    example = true,
                    description = "Return true if session is submitted."
                ),
                @OA\Property(
                    property = "is_expired",
                    type="boolean",
                    example = true,
                    description = "Return true if session is expired."
                ),
                @OA\Property(
                    property = "data",
                    description = "Coding session data - Coding challenge",
                    @OA\Property(
                        property = "rce",
                        description = "rce data",
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
    public function run(Request $request)
    {
        // Get session id
        $session = $request->session_id;
        $code = $request->input('code');
        $language = $request->input('language');
        $inputs = $request->input('inputs');
        $output = $request->input('output');

        // If not code, language, inputs, and output are supply
        if (!$code) {
            return response(array(
                'success' => false,
                'message' => 'please supply code'
            ), Response::HTTP_BAD_REQUEST);
        }

        if (!$language) {
            return response(array(
                'success' => false,
                'message' => 'please supply language'
            ), Response::HTTP_BAD_REQUEST);
        }

        if (!$inputs) {
            return response(array(
                'success' => false,
                'message' => 'please supply inputs'
            ), Response::HTTP_BAD_REQUEST);
        }

        if (!$output) {
            return response(array(
                'success' => false,
                'message' => 'please supply output'
            ), Response::HTTP_BAD_REQUEST);
        }

        // Check in history is started
        $sessionStarted = false;
        $session = CodingSession::where('key', $session)->first();

        if (!$session) {
            return response(array(
                'success' => false,
                'message' => 'Invalid session'
            ), Response::HTTP_BAD_REQUEST);
        }

        $candidacy = $session['candidacy_id'];

        $stage = CandidacyHistory::where([
            'candidacy_id' => $candidacy,
            'metadata->session_key' => $session['key'],
            'status' => 'created'
        ])->get();

        $started = CandidacyHistory::where([
            'candidacy_id' => $candidacy,
            'stage_name' =>  $stage[0]->stage_name,
            'status' => 'started'
        ])->get();

        // Not Started challenge
        if (!$session->started_at) {
            return response([
                'success' => true,
                'session_id' => $session['key'],
                'is_valid' => true,
                'is_started' => false,
                'is_submitted' => false,
                'is_expired' => false,
                'data' => []
            ], Response::HTTP_OK);
        } else {
            $sessionStarted = true;
        }

        // Session is submitted and malicious request
        $submission = CodingSubmission::where(['session_id' => $session->id])->first();
        if ($submission) {
            return response([
                'success' => true,
                'session_id' => $session['key'],
                'is_valid' => true,
                'is_started' => true,
                'is_submitted' => true,
                'is_expired' => true,
                'data' => []
            ], Response::HTTP_OK);
        }

        // Session expired
        if ($sessionStarted && !(strtotime($session->started_at) > strtotime('-' . env('CODING_SESSION_EXPIRY_TIME', 30) . 'minutes'))) {
            return response([
                'success' => true,
                'session_id' => $session['key'],
                'is_valid' => true,
                'is_started' => true,
                'is_submitted' => false,
                'is_expired' => true,
                'data' => []
            ], Response::HTTP_OK);
        }

        // Write code to session
        $session->code = $code;
        $session->language = $language;
        $session->save();

        // send code to RCE
        $client = new \GuzzleHttp\Client(['base_uri' => env('RCE_ENDPOINT', 'http://localhost:5124')]);
        $res = $client->request('POST', '/run', [
            'json' => [
                'code' => $code,
                'language' => $language,
                'input' => $inputs,
                'eo' => $output
            ]
        ]);

        $res = json_decode($res->getBody()->getContents());


        return response([
            'success' => true,
            'session_id' => $session['key'],
            'is_valid' => true,
            'is_started' => true,
            'is_submitted' => false,
            'is_expired' => false,
            'data' => [
                'rce' => $res
            ]
        ], Response::HTTP_OK);
    }

    /**
    @OA\Post(
        path = "/api/v1/coding-sessions/submit/?session_id=ID",
        operationId = "submitCodingSession",
        tags = {"CodingSessions"},
        summary = "Submit coding session and save code to coding session",
        description = "This API fetch coding session by session_id and start the session if start query params supplied",
        @OA\RequestBody(
            required = true,
           @OA\MediaType(
               mediaType="application/json",
               @OA\Schema(
                   @OA\Property(description = "Code that we want to run", property="code", example= "function problem() {}", type="string"),
                   @OA\Property(description = "language of code we want to run", property="language", example= "javascript", type="string"),
                ),
           ),
        ),
        @OA\Response(
            response = "200",
            description = "Session submitted!",
            @OA\JsonContent(
                @OA\Property(
                    property = "success",
                    example = true,
                    type = "boolean",
                    description = "Operation status"
                ),
                @OA\Property(
                    property = "session_id",
                    type="string",
                    example = "ID",
                    description = "Session id"
                ),
                @OA\Property(
                    property = "is_valid",
                    type="boolean",
                    example = true,
                    description = "Return true if session is valid (session entry found in session table)."
                ),
                @OA\Property(
                    property = "is_started",
                    type="boolean",
                    example = true,
                    description = "Return true if session is started."
                ),
                @OA\Property(
                    property = "is_submitted",
                    type="boolean",
                    example = true,
                    description = "Return true if session is submitted."
                ),
                @OA\Property(
                    property = "is_expire",
                    type="boolean",
                    example = true,
                    description = "Return true if session is expired."
                ),
                @OA\Property(
                    property = "data",
                    description = "Coding session data - Coding challenge",
                    example = "[]"
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
    public function submit(Request $request)
    {
        // 1. Get session id
        $session = $request->session_id;
        $code = $request->input('code');
        $language = $request->input('language');

        // If not code and language are supply
        if (!$code) {
            return response(array(
                'success' => false,
                'message' => 'please supply code'
            ), Response::HTTP_BAD_REQUEST);
        }

        if (!$language) {
            return response(array(
                'success' => false,
                'message' => 'please supply language'
            ), Response::HTTP_BAD_REQUEST);
        }

        // Check in history is started
        $sessionStarted = false;
        $session = CodingSession::where('key', $session)->first();
        if (!$session) {
            return response(array(
                'success' => false,
                'message' => 'Invalid session'
            ), Response::HTTP_BAD_REQUEST);
        }

        $candidacyId = $session['candidacy_id'];
        $candidacy = Candidacy::where('id', $candidacyId)->first();
        $candidate = Candidate::where('id', $candidacy->candidate_id)->first();

        $stage = CandidacyHistory::where([
            'candidacy_id' => $candidacyId,
            'metadata->session_key' => $session['key'],
            'status' => 'created'
        ])->get();

        $started = CandidacyHistory::where([
            'candidacy_id' => $candidacyId,
            'stage_name' =>  $stage[0]->stage_name,
            'status' => 'started'
        ])->get();

        // Stated challenge
        if (!$session->started_at) {
            return response([
                'success' => true,
                'session_id' => $session['key'],
                'is_valid' => true,
                'is_started' => false,
                'is_submitted' => false,
                'is_expired' => false,
                'data' => []
            ], Response::HTTP_OK);
        } else {
            $sessionStarted = true;
        }

        // Session is submitted and malicious request
        $submission = CodingSubmission::where(['session_id' => $session->id])->first();
        if ($submission) {
            return response([
                'success' => true,
                'session_id' => $session['key'],
                'is_valid' => true,
                'is_started' => true,
                'is_submitted' => true,
                'is_expired' => true,
                'data' => []
            ], Response::HTTP_OK);
        }

        // Session expired
        if ($sessionStarted && !(strtotime($session->started_at) > strtotime('-' . env('CODING_SESSION_EXPIRY_TIME', 30) . 'minutes'))) {
            return response([
                'success' => true,
                'session_id' => $session['key'],
                'is_valid' => true,
                'is_started' => true,
                'is_submitted' => false,
                'is_expired' => true,
                'data' => []
            ], Response::HTTP_OK);
        }

        // Write code to session
        $session->code = $code;
        $session->language = $language;
        $session->save();

        // write code to submission
        $challenge = CodingChallenge::where(['id' => $session->challenge_id])->get();
        $tests = json_decode($challenge[0]->tests);

        $submission = CodingSubmission::create([
            'session_id' => $session->id,
            'language' => $language,
            'code' => $code,
            'total_tests' => count($tests),
            'passed_tests' => 0,
            'result' => json_encode([
                'crawled' => false,
                'last_result_crawled' => -1,
                'results' => $tests
            ])
        ]);

        $codingSession = CodingSession::where('key', $request->session_id)->get()->first();
        $codingSession->submitted_at = now('UTC');
        $codingSession->save();

        // Entry in history of submission
        CandidacyHistory::create([
            'candidacy_id' => $candidacyId,
            'stage_name' =>  $stage[0]->stage_name,
            'actor' => $candidate->name,
            'status' => 'completed',
            'metadata' => ['session_key' => $session->key, 'submission_key' => $submission->key, 'assignee_key' => $stage[0]["metadata"]["assignee_key"]]
        ]);

        $candidacy->updateStageMetadata();

        if (env('APP_ENV') !== 'testing') {
            Artisan::call("crawl:code", [
                'coding_submission_key' => $submission->key
            ]);
        }

        return response([
            'success' => true,
            'session_id' => $session['key'],
            'is_valid' => true,
            'is_started' => true,
            'is_submitted' => true,
            'is_expired' => false,
            'data' => []
        ], Response::HTTP_OK);
    }
}
