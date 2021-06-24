<?php

namespace Tests\Feature;

use App\Candidacy;
use Tests\TestCase;
use App\CodingSession;
use App\User;
use App\Stage;
use App\CodingChallenge;
use Illuminate\Http\Response;

class CodingSessionControllerTest extends TestCase
{


    ///////////// FETCH SESSION //////////////////

    // When not supplied session_id
    public function testShouldReturnBadRequestWhenSessionIdNotSupply()
    {
        $response = $this->post('/api/v1/coding-sessions/fetch-session/');
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    // Supplied session id but it is invalid
    public function testShouldReturnInvalidSession()
    {
        $response = $this->post('/api/v1/coding-sessions/fetch-session/?session_id=NXYUMS');
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJsonStructure([
            'success',
            'is_valid',
            'message'
        ]);
    }

    // Session is not started
    public function testShouldReturnNotStarted()
    {
        // Create candidacy and assign stage
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
            $response = $this->post('/api/v1/coding-sessions/fetch-session/?session_id=' . $session['key']);

            $response->assertStatus(Response::HTTP_OK)->assertJson([
                'is_started' => false
            ]);
        }
    }

    // Session should be start
    public function testSessionShouldStartWhenStartQueryGiven()
    {
           // Create candidacy and assign stage
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
           }
    }

    ///////////// RUN //////////////////////
    public function shouldRunShowCodeNotSupplyErrorMessage()
    {
        // Create candidacy and assign stage
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
            $response = $this->post('/api/v1/coding-sessions/run/?session_id=' . $session['key'], [
                'language' => 'python'
            ]);

            $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJsonStructure([
                'success',
                'message'
            ]);
        }
    }
    public function shouldRunShowLanguageNotSupplyErrorMessage()
    {
        // Create candidacy and assign stage
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
                'code' => 'code'
            ]);

            $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJsonStructure([
                'success',
                'message'
            ]);
        }
    }
    public function shouldRunShowInputsNotSupplyErrorMessage()
    {
        // Create candidacy and assign stage
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
                'inputs' => 'inputs'
            ]);

            $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJsonStructure([
                'success',
                'message'
            ]);
        }
    }
    public function shouldRunShowOutputNotSupplyErrorMessage()
    {
        // Create candidacy and assign stage
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
                'output' => 'output'
            ]);

            $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJsonStructure([
                'success',
                'message'
            ]);
        }
    }


    ////////////// SUBMIT ////////////////////

    // It should show bad request when code not supply
    public function testShouldShowCodeNotSupplyMessage()
    {
        // Create candidacy and assign stage
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create(["type" => "code"]);

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
                'language' => 'python'
            ]);

            $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJsonStructure([
                'success',
                'message'
            ]);
        }
    }

    // It should show bad request when language not supply
    public function testShouldShowLanguageNotSupplyMessage()
    {
        // Create candidacy and assign stage
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
                'code' => 'code'
            ]);

            $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJsonStructure([
                'success',
                'message'
            ]);
        }
    }

    // Test should submitted when send submit request
    public function testSessionShouldSubmitted()
    {
        // Create candidacy and assign stage
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

            $response->assertStatus(Response::HTTP_OK)->assertJson([
                'is_submitted' => true
            ]);

            // Visible submitted again fetch status
            $response = $this->post('/api/v1/coding-sessions/fetch-session/?session_id=' . $session['key'] . '&start=true');

            $response->assertStatus(Response::HTTP_OK)->assertJson([
                'is_submitted' => true
            ]);
        }
    }
}
