<?php

namespace Tests\Feature;

use App;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Feedback;
use App\Filters\ApiFilter;
use App\User;

class FeedbackTest extends TestCase
{

    //test feedback index useridfilter

    public function testFeedbackListCanbeFilterWithUserId()
    {



        $user1     = factory(App\User::class)->create();
        $user2     = factory(App\User::class)->create();
        $candidacy     = factory(App\Candidacy::class)->create();
        $feedback = factory(App\Feedback::class, 4)->create([
                    'user_id' => $user1->id,
                    'candidacy_id' =>  $candidacy->id
        ]);
        $feedback = factory(App\Feedback::class, 3)->create([
                    'user_id' => $user2->id,
                    'candidacy_id' =>  $candidacy->id
        ]);


        $response = $this->json('get', 'api/v1/candidacies/' . $candidacy->key . '/feedback?user_id=' . $user1->id);
        $response->assertStatus(200);
        $this->assertEquals(4, count($response['data']['feedback']));
    }

    //test feedback index descriptionfilter

    public function testFeedbackListCanbeFilterWithDescription()
    {
        $candidacy     = factory(App\Candidacy::class)->create();
        $feedback = factory(App\Feedback::class, 10)->create([
            'candidacy_id' =>  $candidacy->id
        ]);

        $response = $this->json('get', 'api/v1/candidacies/' . $candidacy->key . '/feedback?description=good')
             ->assertStatus(Response::HTTP_OK);

        $count = App\Feedback::where('description', 'LIKE', "%good%")->count();

        $this->assertEquals($count, count($response['data']['feedback']));
    }

     //test feedback index pagination

    public function testFeedbackListUsesPagination()
    {
        $candidacy     = factory(App\Candidacy::class)->create();
        $feedback = factory(App\Feedback::class, 17)->create([
            'candidacy_id' =>  $candidacy->id
        ]);

        $response = $this->json('get', 'api/v1/candidacies/' . $candidacy->key . '/feedback');
        $response->assertJsonFragment(['per_page' => 15]);

        $response = $this->json('get', 'api/v1/candidacies/' . $candidacy->key . '/feedback?per_page=10');
        $response->assertJsonFragment(['per_page' => 10]);
    }

    //test feedback store

    public function testStoreFeedback()
    {
        $user     = factory(App\User::class)->create();
        $candidacy     = factory(App\Candidacy::class)->create();
        $data = [
            "verdict"     => "Not sure",
            "description" => "Very fast",
            "user_id" => $user->id,
            "candidacy_id" => $candidacy->id,
            "stage_name" => "123",
            "status" => "123",
            "metadata" => ["hello" => "hello"],
        ];

        $response = $this->json('post', "api/v1/candidacies/" . $candidacy->key . "/feedback", $data);
        $response->assertJsonFragment([
            'message' => "The given data was invalid.",
            "errors" => [
                "verdict" => [
                    0 => "The selected verdict is invalid.",
                ],
            ],
        ]);


        $data['verdict'] = 'yes';
        $response = $this->json('post', "api/v1/candidacies/" . $candidacy->key . "/feedback", $data)
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'success' => true
        ]);
    }

    //test feedback show with key

    public function testFeedbackCanbeAccessWithKey()
    {

        $feedback = factory(App\Feedback::class)->create();

        $this->json('get', "api/v1/feedback/" . $feedback->key)
             ->assertStatus(Response::HTTP_OK)
             ->assertJson([
                        'success' => true
             ]);
    }

    //test feedback show with not-exist feedback's key

    public function testFeedbackShowOfMissingFeedback()
    {

        $feedback = factory(App\Feedback::class)->make();

        $this->json('get', "api/v1/feedback/" . $feedback->key)
             ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    //test feedback show with id

    public function testFeedbackCanbeOnlyAccessWithKey()
    {

        $feedback = factory(App\Feedback::class)->create();

        $this->json('get', "api/v1/feedback/" . $feedback->id)
                     ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    // test feedback store with empty feedback field

    public function testFeedbackFeedbackFiledIsRequired()
    {

        $feedback = factory(App\Feedback::class)->create();
        $candidacy     = factory(App\Candidacy::class)->create();


        $data = [
                'description'   => $feedback->description,
                'verdict'       => '',
        ];


        $this->json('post', "api/v1/candidacies/" . $candidacy->key . "/feedback", $data)
             ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJsonValidationErrors('verdict');
    }



    // test feedback store with empty description field

    public function testFeedbackDescriptionFieldIsRequired()
    {

        $feedback = factory(App\Feedback::class)->make();
        $candidacy     = factory(App\Candidacy::class)->create();


        $data = [
                'verdict'      => $feedback->feedback,
                'description'   => '',

        ];


        $this->json('post', "api/v1/candidacies/" . $candidacy->key . "/feedback", $data)
             ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
             ->assertJsonValidationErrors('description');
    }





   // test feedback update with change in feedback filed 000000

    public function testFeedbackFeedbackCanbeUpdated()
    {

        $feedback = factory(App\Feedback::class)->create();

        $data = [
                'verdict'      => 'No',
                'description'   => $feedback->description,
                'user_id'       => $feedback->user_id,
                'candidacy_id'       => $feedback->candidacy_id,



        ];


        $this->json('put', "api/v1/feedback/$feedback->key", $data)
             ->assertStatus(Response::HTTP_OK)
             ->assertJson([
                    'success' => true,

                ]);
    }

    // test feedback update with change in description

    public function testFeedbackDescriptionCanbeUpdated()
    {

        $feedback = factory(App\Feedback::class)->create();

        $data = [
                'verdict'      => $feedback->feedback,
                'description'   => 'Can be better',
                'user_id'       => $feedback->user_id,
                'candidacy_id'       => $feedback->candidacy_id,
                'verdict'  => $feedback->verdict

        ];


        $this->json('put', "api/v1/feedback/$feedback->key", $data)
             ->assertStatus(Response::HTTP_OK)
             ->assertJson([
                    'success' => true,
                    'data' => [
                        'description' => 'Can be better',
                    ]
                ]);
    }

   // test feedback update with change in userid

    public function testFeedbackUseridCanbeUpdated()
    {

        $feedback = factory(App\Feedback::class)->create();

        $user     = factory(App\User::class)->create();

        $data = [
                'verdict'      => $feedback->verdict,
                'description'   => $feedback->description,
                'user_id'       => $user->id,
                'candidacy_id'       => $feedback->candidacy_id,
        ];


        $this->json('put', "api/v1/feedback/$feedback->key", $data)
             ->assertStatus(Response::HTTP_OK)
             ->assertJson([
                        'success' => true,
                        'data' => [
                            'user_id' => $user->id,
                        ]
                ]);
    }

    //test feedback update with not-exist feedback's key

    public function testFeedbackUpdateOfMissingFeedback()
    {

        $feedback = factory(App\Feedback::class)->make();

        $data = [
              'verdict'      => $feedback->verdict,
              'description'   => $feedback->description,
              'user_id'       => $feedback->user_id,
              'candidacy_id'       => $feedback->candidacy_id,
        ];


        $this->json('put', "api/v1/feedback/$feedback->key", $data)
           ->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    // test feedback delete

    public function testFeebackCanbeDeleted()
    {

        $feedback = factory(App\Feedback::class)->create();

        $this->json('delete', "api/v1/feedback/$feedback->key")
             ->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertSoftDeleted('feedback', $feedback->toArray());
    }

    // test feedback delete with not-exist feedback's key

    public function testFeebackDeleteOfMissingFeedback()
    {

        $feedback = factory(App\Feedback::class)->make();

        $this->json('delete', "api/v1/feedback/$feedback->key")
             ->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
