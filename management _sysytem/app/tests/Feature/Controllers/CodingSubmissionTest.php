<?php

namespace Tests\Feature;

use App\Candidacy;
use Tests\TestCase;
use App\CodingSession;
use App\User;
use App\Stage;
use App\CodingChallenge;
use App\CodingSubmission;
use Illuminate\Http\Response;

class CodingSubmissionControllerTest extends TestCase
{

    /**
     * Coding submission not found
     */
    public function testShouldNotReturnCodingSubmission()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        factory(CodingChallenge::class, 10)->create();
        $payload = [
            "metadata" => [
                    "date" => "2021-03-25",
                    "time" => "12:08:28"
            ],
            "assignee_key" => $user->key
        ];
        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_CREATED);

        $session = CodingSession::where('candidacy_id', $candidacy->id)->first();

        if ($session) {
            // Session should not be started
            $response = $this->post('/api/v1/coding-sessions/fetch-session/?session_id=' . $session['key']);

            $response->assertStatus(Response::HTTP_OK)->assertJson([
                'is_started' => false
            ]);

            // Session should start
            $response = $this->post('/api/v1/coding-sessions/fetch-session/?session_id=' . $session['key'] . '&start=true');

            $response->assertStatus(Response::HTTP_OK)->assertJson([
                'is_started' => true
            ]);

            // Submit session
            $response = $this->post('/api/v1/coding-sessions/submit/?session_id=' . $session['key'], [
                'code' => 'code',
                'language' => 'python'
            ]);

            // Read submission
            $response = $this->get('/api/v1/coding-submissions/AABBCC');
            $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
                'success' => false
            ])->assertJsonStructure([
                'success',
                'message'
            ]);
        }
    }
    /**
     * Coding submission found
     */
    public function testShouldReturnCodingSubmission()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        factory(CodingChallenge::class, 10)->create();
        $payload = [
            "metadata" => [
                    "date" => "2021-03-25",
                    "time" => "12:08:28"
            ],
            "assignee_key" => $user->key
        ];
        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_CREATED);

        $session = CodingSession::where('candidacy_id', $candidacy->id)->first();

        if ($session) {
            // Session should not be started
            $response = $this->post('/api/v1/coding-sessions/fetch-session/?session_id=' . $session->key);

            $response->assertStatus(Response::HTTP_OK)->assertJson([
                'is_started' => false
            ]);

            // Session should start
            $response = $this->post('/api/v1/coding-sessions/fetch-session/?session_id=' . $session['key'] . '&start=true');

            $response->assertStatus(Response::HTTP_OK)->assertJson([
                'is_started' => true
            ]);

            // Submit session
            $response = $this->post('/api/v1/coding-sessions/submit/?session_id=' . $session['key'], [
                'code' => 'code',
                'language' => 'python'
            ]);

            $response->assertStatus(Response::HTTP_OK)->assertJson([
                'is_submitted' => true
            ]);

            // Visible submitted again fetch status
            $response = $this->post('/api/v1/coding-sessions/fetch-session/?session_id=' . $session['key'] . '&start=true');

            $response->assertStatus(Response::HTTP_OK)->assertJson([
                'is_submitted' => true
            ]);

            // Read submission
            $submission = CodingSubmission::where('session_id', $session->id)->first();
            $response = $this->get('/api/v1/coding-submissions/' . $submission->key);
            $response->assertJson([
                'success' => true
            ])->assertJsonStructure([
                'success',
                'message',
                'data'
            ]);
        }
    }
}
