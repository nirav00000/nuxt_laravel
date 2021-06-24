<?php

namespace Tests\Feature\Controllers;

use App\Questionnaire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class QuestionnaireControllerTest extends TestCase
{
    /*All questionnaire are retrieved successfully */
    public function testAllQuestionnairesAreRetrievedSuccessfully()
    {
        $questionnaires = factory(Questionnaire::class, 10)->create();

        //asserttions
        $response = $this->json('get', '/api/v1/questionnaires');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee("Questionnaires retreived successfully");
    }


    public function testNewQuestionnairAdding()
    {
        $data = [
            "name" => "test questionnair"
        ];
        $response = $this->post('/api/v1/questionnaires', $data);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson(['success' => true]);
        $response->assertJson(['message' => "Questionnaire created successfully."]);
        $response->assertJson(['data' => $data]);
    }

    public function testNewEmptyQuestionnairAdding()
    {
        $response = $this->post('/api/v1/questionnaires', []);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => "The name field is required."]);
    }

    public function testUpdateQuestionnairAdding()
    {
        $questionnair = factory(Questionnaire::class)
            ->create(['name' => "before"]);
        $data = [
            "name" => "after"
        ];
        $this->assertEquals($questionnair->name, "before");
        $response = $this->put('/api/v1/questionnaires/' . $questionnair->key, $data);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['success' => true]);
        $response->assertJson(['message' => "Questionnaire updated successfully."]);
        $questionnair->refresh();
        $this->assertEquals($questionnair->name, "after");
    }

    public function testUpdateWithEmptyNameQuestionnairAdding()
    {
        $questionnair = factory(Questionnaire::class)
            ->create(['name' => "before"]);
        $response = $this->put('/api/v1/questionnaires/' . $questionnair->key, []);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['success' => false]);
        $response->assertJson(['message' => "The name field is required."]);
    }

    public function testQuestionnairDelete()
    {
        $questionnair = factory(Questionnaire::class)
            ->create(['name' => "before"]);
        $response = $this->delete('/api/v1/questionnaires/' . $questionnair->key);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['success' => true]);
        $this->assertNull(Questionnaire::find($questionnair->id));
    }

    public function testQuestionnairDeleteDBCount()
    {
        $count = Questionnaire::count();
        $questionnair = factory(Questionnaire::class)
            ->create(['name' => "before"]);
        $this->assertTrue(Questionnaire::count() == $count + 1);
        $response = $this->delete('/api/v1/questionnaires/' . $questionnair->key);
        $this->assertTrue(Questionnaire::count() == $count);
    }

    public function testQuestionnairDeleteAsSoftdel()
    {
        $questionnair = factory(Questionnaire::class)
            ->create(['name' => "before"]);
        $response = $this->delete('/api/v1/questionnaires/' . $questionnair->key);
        $this->assertNull(Questionnaire::find($questionnair->id));
        $deleted = Questionnaire::withTrashed()->find($questionnair->id);
        $this->assertNotNull($deleted);
        $this->assertTrue($deleted->name == "before");
    }
}
