<?php

namespace Tests\Feature\Controllers;

use App\Candidacy;
use App\CandidacyHistory;
use App\Questionnaire;
use App\QuestionnaireSubmission;
use App\Stage;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class QuestionnaireSubmissionControllerTest extends TestCase
{
    protected static $payload = [
        "email" => "casimir55@example.net",
        "data" => [
            [
                "question" => "Tell me something about yourself",
                "answer" => "I am a Computer Engineer who likes to play cricket"
            ],
            [
                "question" => "What are your hobbies?",
                "answer" => "Exploring new tech"
            ]
        ]
    ];
    /* API works when all request data is given and there exists a questionnaire */
    public function testSubmissionIsDoneWhenRequestHasAllTheDataAndQuestionnaireIsPresentInDatabase()
    {
        $candidacy = factory(Candidacy::class)->create(['final_status' => 'active']);
        $payload = self::$payload;
        $questionnaire = factory(Questionnaire::class)->create();
        $stage = factory(Stage::class)->create(["type" => "questionnaire","metadata" => ["questionnaire_key" => $questionnaire->key]]);
        $payload["email"] = $candidacy->candidate->email;
        $user = factory(User::class)->create();
        $payload["assignee_key"] = $user->key;
        $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response = $this->json('post', '/api/v1/questionnaire_submissions/' . $questionnaire->key, $payload);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertCount(1, QuestionnaireSubmission::all());
        $this->assertCount(2, CandidacyHistory::all());
    }
    /* No submission taken for inactive candidacy */
    public function testSubmissionNotTakenIfCandidacyIsInactive()
    {
        $candidacy = factory(Candidacy::class)->create(['final_status' => 'inactive']);
        $payload = self::$payload;
        $questionnaire = factory(Questionnaire::class)->create();
        $payload["email"] = $candidacy->candidate->email;
        $response = $this->json('post', '/api/v1/questionnaire_submissions/' . $questionnaire->key, $payload);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }
    /**questionnaire submission retrieved successfully*/
    public function testQuestionnaireSubmissionIsRetrievedSuccessfully()
    {
        $candidacy = factory(Candidacy::class)->create(['final_status' => 'active']);
        $payload = self::$payload;
        $questionnaire = factory(Questionnaire::class)->create();
        $stage = factory(Stage::class)->create(["type" => "questionnaire","metadata" => ["questionnaire_key" => $questionnaire->key]]);
        $payload["email"] = $candidacy->candidate->email;
        $user = factory(User::class)->create();
        $payload["assignee_key"] = $user->key;
        $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response = $this->json('post', '/api/v1/questionnaire_submissions/' . $questionnaire->key, $payload);
        $questionnaire_submission = QuestionnaireSubmission::first();

        $response = $this->json('get', '/api/v1/questionnaire_submissions/' . $questionnaire_submission->key);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee("Questionnaire Submission retrieved succesfully");
        $response->assertSee($questionnaire_submission->key);
    }
}
