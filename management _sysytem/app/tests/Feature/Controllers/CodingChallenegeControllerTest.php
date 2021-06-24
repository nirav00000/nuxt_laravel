<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\CodingChallenge;
use Illuminate\Http\Response;

class CodingChallenegeControllerTest extends TestCase
{

    // GET /api/v1/coding-challenges


    /**
     * GET /api/v1/coding-challenges/
     * In this test we create 10 coding challenges and try to status and total is valid
     * @return void
     */
    public function testShouldReturnListOfCodingChallengesInValidFormat()
    {
        Factory(CodingChallenge::class, 10)->create();

        $response = $this->get('/api/v1/coding-challenges');

        $response->assertStatus(Response::HTTP_OK)->assertJson(
            [
                'success' => true,
                'data'    => [
                    'meta' => [
                        'total' => 10,
                    ],
                ],
            ]
        );
    }


     /**
      * GET /api/v1/coding-challenges/?page=2
      * In this test we create 15 coding challenges and try to status and total is valid
      * @return void
      */
    public function testShouldReturnListOfCodingChallengesValidFormatUsingPagination()
    {
        Factory(CodingChallenge::class, 15)->create();

        $response = $this->get('/api/v1/coding-challenges/?page=2');

        $response->assertStatus(Response::HTTP_OK)->assertJson(
            [
                'success' => true,
                'data'    => [
                    'meta' => [
                        'current_page' => 2,
                        'from'         => 11,
                        'to'           => 15,
                        'total'        => 15,
                    ],
                ],
            ]
        );
    }


    // POST /api/v1/coding-challenges


    /**
     * POST /api/v1/coding-challenges
     * Create a Coding Challenge
     * This is a valid request to create coding challenge
     */
    public function testShouldCreateValidCodingChallenge()
    {
        $response = $this->postJson(
            '/api/v1/coding-challenges/',
            [
                'title'       => 'title',
                'description' => 'something',
                'tests'       => [['inputs' => '2, 3', 'output' => '5'], ['inputs' => '0, 2', 'output' => '2']],
            ]
        );
        $response->assertStatus(Response::HTTP_CREATED)->assertJson(
            [
                'success' => true,
                'message' => 'Challenge created'
            ]
        );
    }


    /**
     * POST /api/v1/coding-challenges
     * Create a invalid title Coding Challenge
     */
    public function testShouldNotCreateInValidTitleCodingChallenge()
    {
        $response = $this->postJson(
            '/api/v1/coding-challenges/',
            [
                'title'       => '',
                'description' => 'something',
                'tests'       => [['inputs' => 'hello', 'output' => 'hello']],
            ]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'errors'
        ]);
    }


     /**
      * POST /api/v1/coding-challenges
      * Create a invalid description Coding Challenge
      */
    public function testShouldNotCreateInValidDescriptionCodingChallenge()
    {
        $response = $this->postJson(
            '/api/v1/coding-challenges/',
            [
                'title'       => 'Hello',
                'description' => '',
                'tests'       => [['inputs' => '', 'output' => 'hello']],
            ]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'errors'
        ]);
    }


    /**
     * POST /api/v1/coding-challenges
     * Create a Coding Challenge
     * This is a valid request to create coding challenge
     */
    public function testShouldNotCreateInValidTestsInputCodingChallenge()
    {
        $response = $this->postJson(
            '/api/v1/coding-challenges/',
            [
                'title'       => 'title',
                'description' => 'something',
                'tests'       => [['inputs' => '', 'output' => 'hello']],
            ]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'errors'
        ]);
    }


    // GET /api/v1/coding-challenges/{codingchallenge}


    /**
     * GET /api/v1/coding-challenges/{codingchallenge}
     * Get a created coding challenge
     */
    public function testShouldReturnCodingChallenge()
    {
        $challenge = Factory(CodingChallenge::class)->create();
        // Get key of created coding challenge
        $key      = $challenge->key;
        $response = $this->get('/api/v1/coding-challenges/' . $key);
        $response->assertStatus(Response::HTTP_OK)->assertJson(
            [
                'success' => true,
                'message' => 'Coding Challenge retrived successfully',
                'data' => [
                    'key' => $key
                ]
            ]
        );
    }


    /**
     * GET /api/v1/coding-challenges/{codingchallenge}
     * Get a not created coding challenge
     */
    public function testShouldNotReturnCodingChallenge()
    {
        $response = $this->get('/api/v1/coding-challenges/' . "somekey");
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJsonStructure(
            [
                'success',
                'message'
            ]
        );
    }


    // Update created coding challenges
    // PUT /api/v1/coding-challenges/{codingchallenge}
    // update whole challenge
    public function testShouldCreatedUpdateCodingChallenge()
    {
        $challenge = Factory(CodingChallenge::class)->create();
        // Get key of created coding challenge
        $key      = $challenge->key;
        $response = $this->putJson(
            '/api/v1/coding-challenges/' . $key,
            [
                'title'       => 'title',
                'description' => 'something',
                'tests'       => [['inputs' => '3, 5', 'output' => '8']],
            ]
        );
        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'success' => true,
            'message' => 'Challenge updated',
            'data' => array(
                'key' => $key
            )
        ]);
    }


    // Challege has exists and try to update with invalid title
    public function testShouldNotUpdateCodingChallengeWithInvalidTitle()
    {
        $challenge = Factory(CodingChallenge::class)->create();
        // Get key of created coding challenge
        $key      = $challenge->key;
        $response = $this->putJson(
            '/api/v1/coding-challenges/' . $key,
            [
                'title'       => '',
                'description' => 'something',
                'tests'       => [['inputs' => 'hello', 'output' => 'hello']],
            ]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'errors'
        ]);
    }


      // Challege has exists and try to update with invalid description
    public function testShouldNotUpdateCodingChallengeWithInvalidDescription()
    {
        $challenge = Factory(CodingChallenge::class)->create();
        // Get key of created coding challenge
        $key      = $challenge->key;
        $response = $this->putJson(
            '/api/v1/coding-challenges/' . $key,
            [
                'title'       => 'something',
                'description' => '',
                'tests'       => [['inputs' => 'hello', 'output' => 'hello']],
            ]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'errors'
        ]);
    }


      // Challege has exists and try to update with invalid test
    public function testShouldNotUpdateCodingChallengeWithInvalidTest()
    {
        $challenge = Factory(CodingChallenge::class)->create();
        // Get key of created coding challenge
        $key      = $challenge->key;
        $response = $this->putJson(
            '/api/v1/coding-challenges/' . $key,
            [
                'title'       => 'something',
                'description' => 'Hello',
                'tests'       => [['inputs' => 'hello', 'output' => 'hello'], ['inputs' => '']],
            ]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'errors'
        ]);
    }


    // PATCH /api/v1/codingchallenges/{codingchallenge}
    // update particular challenge field
    // update whole challenge using PATCH
    public function testShouldUpdateWholeChallenegeWithValidAll()
    {
        $challenge = Factory(CodingChallenge::class)->create();
        // Get key of created coding challenge
        $key      = $challenge->key;
        $response = $this->patchJson(
            '/api/v1/coding-challenges/' . $key,
            [
                'title'       => 'title',
                'description' => 'something',
                'tests'       => [['inputs' => 'hello', 'output' => 'hello']],
            ]
        );
        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'success' => true,
            'message' => 'Challenge updated',
            'data' => array(
                'key' => $key
            )
        ]);
    }


    // Challege has exists and try to update with valid title
    public function testShouldUpdateValidTitle()
    {
        $challenge = Factory(CodingChallenge::class)->create();
          // Get key of created coding challenge
        $key    = $challenge->key;
        $response = $this->patchJson(
            '/api/v1/coding-challenges/' . $key,
            [
                'title' => 'Hello',
            ]
        );
        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'success' => true,
            'message' => 'Challenge updated',
            'data' => array(
                'key' => $key
            )
        ]);
    }


      // Challege has exists and try to update with invalid description
    public function testShouldUpdateValidDescription()
    {
        $challenge = Factory(CodingChallenge::class)->create();
        // Get key of created coding challenge
        $key    = $challenge->key;
        $response = $this->patchJson(
            '/api/v1/coding-challenges/' . $key,
            [
                'description' => 'World',
            ]
        );
        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'success' => true,
            'message' => 'Challenge updated',
            'data' => array(
                'key' => $key
            )
        ]);
    }


      // Challege has exists and try to update with invalid test
    public function testShouldUpdateValidTests()
    {
        $challenge = Factory(CodingChallenge::class)->create();
        // Get key of created coding challenge
        $key    = $challenge->key;
        $response = $this->patchJson(
            '/api/v1/coding-challenges/' . $key,
            [
                'tests' => [['inputs' => 'hello', 'output' => 'hello'], ['inputs' => 'world', 'output' => 'Nice']],
            ]
        );
        $response->assertStatus(Response::HTTP_OK)->assertJson([
            'success' => true,
            'message' => 'Challenge updated',
            'data' => array(
                'key' => $key
            )
        ]);
    }


    // Challege has exists and try to update with invalid test
    public function testShouldUpdateInvalidTests()
    {
        $challenge = Factory(CodingChallenge::class)->create();
        // Get key of created coding challenge
        $key    = $challenge->key;
        $response = $this->patchJson(
            '/api/v1/coding-challenges/' . $key,
            [
                'tests' => [['inputs' => ["ab"], 'output' => 'hello'], ['inputs' => ["cd"]]],
            ]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'message',
            'errors'
        ]);
    }


    // DELETE /api/v1/coding-challenges/{codingChallenge}


    /**
     * Delete challenge that exist
     */
    public function shouldDeleteChallenge()
    {
        $challenge = Factory(CodingChallenge::class)->create();
        // Get key of created coding challenge
        $key      = $challenge->key;
        $response = $this->delete('/api/v1/coding-challenges/' . $key);
        $response->assertStatus(Response::HTTP_OK);
        $response->$this->get('/api/v1/coding-challenge/' . $key);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'success' => false,
            'message' => 'Record not found.'
        ]);
    }


    /**
     * Delete challenge that not exist
     */
    public function shouldNotDeleteChallenge()
    {
        $response = $this->delete('/api/v1/coding-challenges/notexist');
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'success' => false,
            'message' => 'Record not found.'
        ]);
    }
}
