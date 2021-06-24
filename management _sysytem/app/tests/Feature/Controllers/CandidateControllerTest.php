<?php

namespace Tests\Feature\Candidate;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Candidate;
use App\Candidacy;
use App;
use Illuminate\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class CandidateControllerTest extends TestCase
{
    use WithFaker;


    /**fetch all candidates*/
    public function testFetchListOfCandidatesFromDatabaseInCorrectFormat()
    {

        $candidate = factory(Candidate::class)->create();

        $response = $this->json('get', '/api/v1/candidates');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'success',
                'message',
                'data',
            ]
        );
    }


    /**fetch one candidate by key*/
    public function testFetchSpecificCandidateByKey()
    {
        $candidate = factory(Candidate::class)->create();
        $response  = $this->json('get', '/api/v1/candidates/' . $candidate->key);
        $response->assertStatus(Response::HTTP_OK);
    }


    /**candidate cannot be accessed by id*/
    public function testNotAbleToAccessCandidateById()
    {
        $candidate = factory(Candidate::class)->create();
        $response  = $this->json('get', '/api/v1/candidates/' . $candidate->id);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    /**all data is validated while insertion*/
    public function testFieldsAreValidatedWhileInsert()
    {
        $payload = [
            "name"     => "nameXYZ",
            "email"    => "asd@gmail.me",
            "metadata" => [],
        ];

        $response = $this->json('post', '/api/v1/candidates', $payload);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(
            [
                'success',
                'message',
                'data',
            ]
        );
    }


    /**name field is required for insertion*/
    public function testNameFieldIsRequiredWhileInsert()
    {
        $candidate       = factory(Candidate::class)->make();
        $candidate->name = '';
        $response        = $this->json('post', '/api/v1/candidates', $candidate->toArray());
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**email field is required for insertion*/
    public function testEmailFieldIsRequiredWhileInsert()
    {
        $candidate        = factory(Candidate::class)->make();
        $candidate->email = '';
        $response         = $this->json('post', '/api/v1/candidates', $candidate->toArray());
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**all data is validated while updation*/
    public function testFieldsAreValidatedWhileUpdate()
    {
        $candidate = factory(Candidate::class)->create();

        $payload = [
            "name"     => "nameXYZ",
            "email"    => "asd@gmail.me",
            "metadata" => [],
        ];

        $response = $this->json('put', '/api/v1/candidates/' . $candidate->key, $payload);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'success',
                'message',
                'data',
            ]
        );
    }


    /**name field is required for updation*/
    public function testNameFieldIsRequiredWhileUpdate()
    {
        $candidate       = factory(Candidate::class)->create();
        $candidate->name = '';
        $response        = $this->json('put', '/api/v1/candidates/' . $candidate->key, $candidate->toArray());
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**email field is required for updation*/
    public function testEmailFieldIsRequiredWhileUpdate()
    {
        $candidate        = factory(Candidate::class)->create();
        $candidate->email = '';
        $response         = $this->json('put', '/api/v1/candidates/' . $candidate->key, $candidate->toArray());
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**do not update data with id*/
    public function testDataNotUpdatedWithId()
    {
        $candidate       = factory(Candidate::class)->create();
        $candidate->name = 'Updated Name';
        $response        = $this->json('put', '/api/v1/candidates/' . $candidate->id, $candidate->toArray());
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /*update resumes in metadata of candidacy if given in request */
    public function testUpdateResumesInMetadataOfCandidacyIfGivenInRequest()
    {
        $candidate = factory(Candidate::class)->create();
        $candidacy = factory(Candidacy::class)->create(['final_status' => 'active','candidate_id' => $candidate->id]);
        // $candidate = $candidacy->candidate;

        $payload = [
            "name"     => "nameXYZ",
            "email"    => "asd@gmail.me",
            "metadata" => [],
            "candidacy_key" => $candidacy->key,
            "candidacy_resume" => "https://resume1.com/11.pdf",

        ];

        $response = $this->json('put', '/api/v1/candidates/' . $candidate->key, $payload);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertCount(1, Candidacy::first()->metadata["resumes"]);
    }

    /**data deleted with key*/
    public function testDeleteACandidateWithKey()
    {
        $candidate = factory(Candidate::class)->create();
        $response  = $this->json('delete', '/api/v1/candidates/' . $candidate->key);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'success',
                'message',
                'data',
            ]
        );
    }


    /**data  not deleted with id*/
    public function testDataNotDeletedWithId()
    {
        $candidate = factory(Candidate::class)->create();
        $response  = $this->json('delete', '/api/v1/candidates/' . $candidate->id);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    /**deleted data cannot be accessed*/
    public function testNotAbleToAccessDeletedData()
    {
        $candidate = factory(Candidate::class)->create();
        $candidate->delete();
        $response = $this->json('get', '/api/v1/candidates/' . $candidate->key);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
