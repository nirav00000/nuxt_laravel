<?php

namespace Tests\Feature\Controllers;

use App\Candidacy;
use App\CandidacyHistory;
use App\CodingChallenge;
use App\Stage;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CandidacyHistoryControllerTest extends TestCase
{
    /**returns all histories when key is given in query parameter*/
    public function testKeyValueInQueryParameterGivesResults()
    {
        //assign stage
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        factory(CodingChallenge::class, 10)->create(); //solve the error when stage type is code
        $this->assignStage($candidacy, $user, $stage);
        //close candidacy
        $this->closeCandidacy($candidacy, $user);

        $response = $this->json('get', '/api/v1/admin/candidacy_histories/' . $candidacy->key, [], ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(["message" => "Histories retrieved successfully"]);
        $this->assertTrue($response->original->data["histories"]->count() === 2);
    }


    /**assign stage for testing*/
    public function assignStage($candidacy, $user, $stage)
    {
        $payload = [
            "assignee_key" => $user->key
        ];

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        return $response;
    }


    /**close candidacy for testing*/
    public function closeCandidacy($candidacy)
    {
        $payload = [
            "candidacy_closing_reason" => "A sample reason for test",
        ];
        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/close', $payload, ['group' => config("ldap.admin")]);
    }
}
