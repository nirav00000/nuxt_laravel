<?php

namespace Tests\Feature\Stage;

use App;
use App\Questionnaire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Stage;
use Illuminate\Http\Response;

class StageControllerTest extends TestCase
{


    /**fetch all stages*/
    public function testGetListOfStagesInProperFormat()
    {
        $stage    = factory(Stage::class, 20)->create();
        $response = $this->json('get', '/api/v1/stages');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                "success",
                "message",
                "data",
            ]
        );
    }


    /**fetch one stage by key authjwt*/
    public function testGetOneStageByKey()
    {
        $stage    = factory(Stage::class)->create();
        $response = $this->json('get', '/api/v1/stages/' . $stage->key);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            [
                'data' => [
                    'stage' => [
                        'key'      => $stage->key,
                        'name'     => $stage->name,
                        'type'     => $stage->type,
                        'metadata' => $stage->metadata,
                    ],
                ],
            ]
        );
    }


    /**stage data cannot be accessed by id*/
    public function testStageDataNotAccessedById()
    {
        $stage    = factory(Stage::class)->create();
        $response = $this->json('get', '/api/v1/stages/' . $stage->id);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    /**all data is validated while insertion*/
    public function testDataIsValidatedWhileInsertion()
    {
        $payload = [
            'name'     => "name XYZ",
            'type'     => "interview",
            'metadata' => json_encode(['key1' => 'value1', 'key2' => 'value2']),
        ];

        $response = $this->json('post', '/api/v1/stages', $payload);
        $response->assertJson(
            [
                'data' => [
                    'stage' => [
                        'name'     => "name XYZ",
                        'type'     => "interview",
                        'metadata' => json_encode(['key1' => 'value1', 'key2' => 'value2']),
                    ],
                ],
            ]
        );
    }


    /**name field is required while insertion*/
    public function testNameFieldIsRequiredWhileInsertion()
    {
        $payload = [
            'name'     => "name XYZ",
            'type'     => "",
            'metadata' => json_encode(['key1' => 'value1', 'key2' => 'value2']),
        ];
        $response = $this->json('post', '/api/v1/stages', $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**type field is required while insertion*/
    public function testTypeFieldIsRequiredWhileInsertion()
    {
        $payload = [
            'name'     => "name XYZ",
            'type'     => "",
            'metadata' => json_encode(['key1' => 'value1', 'key2' => 'value2']),
        ];

        $response = $this->json('post', '/api/v1/stages', $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $payload['type'] = "questioner";
        $response = $this->json('post', '/api/v1/stages', $payload);

        $response->assertJsonFragment([
            'message' => "The given data was invalid.",
            "errors" => [
                "type" => [
                    0 => "The selected type is invalid.",
                ],
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**all data is validated while updation*/
    public function testDataIsValidatedWhileUpdation()
    {
        $stage           = factory(Stage::class)->create();
        $stage->name     = "nameXYZ";
        $stage->type     = "code";
        $stage->metadata = json_encode(["key1" => "val1"]);

        $response = $this->json('put', '/api/v1/stages/' . $stage->key, $stage->toArray());
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('success', true);
        $response->assertJson(
            [
                'data' => [
                    'stage' => [
                        'key'      => $stage->key,
                        'name'     => $stage->name,
                        'type'     => $stage->type,
                        'metadata' => $stage->metadata,
                    ],
                ],
            ]
        );
    }


    /**name field is required for updation*/
    public function testNameFieldIsRequiredWhileUpdation()
    {
        $stage       = factory(Stage::class)->create();
        $stage->name = "";
        $stage->save();

        $response = $this->json('put', '/api/v1/stages/' . $stage->key, $stage->toArray());
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**type field is required for updation*/
    public function testTypeFieldIsRequiredWhileUpdation()
    {
        $stage       = factory(Stage::class)->create();
        $stage->type = "";
        $stage->save();

        $response = $this->json('put', '/api/v1/stages/' . $stage->key, $stage->toArray());
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**do not update data with id*/
    public function testDataNotUpdatedUsingId()
    {
        $stage       = factory(Stage::class)->create();
        $stage->type = "code";
        $stage->save();

        $response = $this->json('put', '/api/v1/stages/' . $stage->id, $stage->toArray());
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    /**data deleted with key jwtauth*/
    public function testDataCanBeDeletedWithKey()
    {
        $stage    = factory(Stage::class)->create();
        $response = $this->json('delete', '/api/v1/stages/' . $stage->key);
        $response->assertStatus(Response::HTTP_OK);
    }


    /**data  not deleted with id*/
    public function testDataCanNotBeDeletedWithId()
    {
        $stage    = factory(Stage::class)->create();
        $response = $this->json('delete', '/api/v1/stages/' . $stage->id);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    /**deleted data cannot be accessed*/
    public function testDeletedDataCanNotBeAccessed()
    {
        $stage = factory(Stage::class)->create();
        $stage->delete();
        $response = $this->json('get', '/api/v1/stages/' . $stage->key);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    /**data is soft deleted, not permanently*/
    public function testDataIsSoftDeleted()
    {
        $stage = factory(Stage::class)->create();
        $stage->delete();
        $response = $this->json('get', '/api/v1/stages/' . $stage->key);
        $this->assertTrue($stage->trashed());
    }

    /**questionnaire_key is required while update of stage type questionnaire */
    public function testQuestionnaireKeyIsRequiredWhileUpdatingQuestionnaireStage()
    {
        $stage = factory(Stage::class)->create();
        $payload = [
            "name" => "Software Dev Questionnaire",
            "type" => "questionnaire"
        ];

        $response = $this->json('put', 'api/v1/stages/' . $stage->key, $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**questionnaire stage updation works when questionnaire key given  */
    public function testQuestionnaireStageUpdationWorksWhenQuestionnaireKeyIsGiven()
    {
        $stage = factory(Stage::class)->create();
        $questionnaire = factory(Questionnaire::class)->create();
        $payload = [
            "name" => "Software Dev Questionnaire",
            "type" => "questionnaire",
            "questionnaire_key" => $questionnaire->key,
        ];

        $response = $this->json('put', 'api/v1/stages/' . $stage->key, $payload);
        $response->assertStatus(Response::HTTP_OK);
    }
}
