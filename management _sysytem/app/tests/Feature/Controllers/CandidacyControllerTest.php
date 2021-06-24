<?php

namespace Tests\Feature\Candidacy;

use App\Candidacy;
use App\CodingChallenge;
use App\Questionnaire;
use App\Stage;
use App\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

use function PHPUnit\Framework\assertJson;

class CandidacyControllerTest extends TestCase
{


    /**get list of all candidacies*/
    public function testGetListOfCandidaciesInValidFormat()
    {
        $candidacy = factory(Candidacy::class, 10)->create();

        $response = $this->json('get', '/api/v1/admin/candidacies', [], ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'success',
                'message',
                'data',
            ]
        );
    }


    /**get list of candidacies with filter final_status */
    public function testGetListOfCandidaciesWithFinalStatusFilter()
    {
        factory(Candidacy::class, 10)->create(["final_status" => "inactive"]);
        factory(Candidacy::class, 11)->create(["final_status" => "active"]);

        // for inactive filter
        $response = $this->json('get', '/api/v1/admin/candidacies?final_status=inactive', [], ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            [
                "data" => [
                    "meta" => [
                        "total" => 10,
                    ],
                ],
            ]
        );
        // for active filter
        $response = $this->json('get', '/api/v1/admin/candidacies?final_status=active', [], ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            [
                "data" => [
                    "meta" => [
                        "total" => 11,
                    ],
                ],
            ]
        );
        // for all filter
        $response = $this->json('get', '/api/v1/admin/candidacies?final_status=all', [], ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            [
                "data" => [
                    "meta" => [
                        "total" => 21,
                    ],
                ],
            ]
        );
        // without filter it returns active
        $response = $this->json('get', '/api/v1/admin/candidacies', [], ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            [
                "data" => [
                    "meta" => [
                        "total" => 11   ,
                    ],
                ],
            ]
        );
    }


    /**test for position filter*/
    public function testPositionFilterIsWorking()
    {
        factory(Candidacy::class, 10)->create(["position" => "Software Developer"]);
        factory(Candidacy::class, 11)->create(["position" => "Devops"]);
        // final_status filter must be all to get all candidacies
        $response = $this->json('get', '/api/v1/admin/candidacies?final_status=all&position=Devops', [], ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            [
                "data" => [
                    "meta" => [
                        "total" => 11,
                    ],
                ],
            ]
        );
    }


    /**test for candidate_name AdvancedFilter*/
    public function testCandidateNameFilterIsWorking()
    {
        factory(Candidacy::class, 10)->create();
        $name = Candidacy::first()->candidate->name;
        // final_status filter must be all to get all candidacies
        $response = $this->json('get', '/api/v1/admin/candidacies?final_status=all&candidate_name=' . $name, [], ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            [
                "data" => [
                    "meta" => [
                        "total" => 1,
                    ],
                ],
            ]
        );
    }


    /**able to retrieve one candidacy with key*/
    public function testGetOneCandidacyWithKey()
    {
        $candidacy = factory(Candidacy::class)->create();
        $response  = $this->json('get', '/api/v1/candidacies/' . $candidacy->key);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'success',
                'message',
                'data',
            ]
        );
    }


    /**no records can be accessed by candidacy id*/
    public function testNoRecordsCanBeAccessedWithCandidacyId()
    {
        $candidacy = factory(Candidacy::class)->create();
        $response  = $this->json('get', '/api/v1/candidacies/' . $candidacy->id);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    /**create new candidacy with valid input*/
    public function testValidInputIsAbleToCreateNewCandidacy()
    {
        $payload = [
            "name"     => "testName",
            "email"    => "test@test.com",
            "metadata" => [],
            "position" => "Software Developer",
        ];

        $response = $this->json('post', '/api/v1/candidacies', $payload);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure(
            [
                "success",
                "message",
                "data",
            ]
        );
    }


    /**name field is validated while insertion*/
    public function testNameFieldIsRequiredWhileInsertion()
    {
        $payload = [
            "name"     => "",
            "email"    => "test@test.com",
            "metadata" => [],
            "position" => "Software Developer",
        ];

        $response = $this->json('post', '/api/v1/candidacies', $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**email field is validated while insertion*/
    public function testEmailFieldIsRequiredWhileInsertion()
    {
        $payload = [
            "name"     => "testName",
            "email"    => "",
            "metadata" => [],
            "position" => "Software Developer",
        ];

        $response = $this->json('post', '/api/v1/candidacies', $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**email should be in valid format while insertion*/
    public function testEmailShouldBeInValidFormatWhileInsertion()
    {
        $payload = [
            "name"     => "testName",
            "email"    => "test.com",
            "metadata" => [],
            "position" => "Software Developer",
        ];

        $response = $this->json('post', '/api/v1/candidacies', $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**position field is required while insertion*/
    public function testPositionFieldIsRequiredWhileInsertion()
    {
        $payload = [
            "name"     => "testName",
            "email"    => "test@test.com",
            "metadata" => [],
            "position" => "",
        ];

        $response = $this->json('post', '/api/v1/candidacies', $payload);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**only one candidacy is active for one candidate*/
    public function testOnlyOneActiveCandidacyForOneCandidate()
    {
        $payload = [
            "name"     => "testName",
            "email"    => "test@test.com",
            "metadata" => [],
            "position" => "Software Developer",
        ];
        for ($i = 0; $i < 10; $i++) {
            $this->json('post', '/api/v1/candidacies', $payload);
        }

        $response = $this->json('post', '/api/v1/candidacies', $payload);
        $active   = Candidacy::where('final_status', 'active')->count();
        $this->assertTrue($active == 1);
    }


    /**................................stage assignment test cases...............................*/

    /** Stage cannot be assigned by normal user */
    public function testStageNotAssignedByNormalUser()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        factory(CodingChallenge::class, 10)->create(); //solve the error when stage type is code
        $payload = [
            "metadata" => [
                    "date" => "2021-03-25",
                    "time" => "12:08:28"
            ],
            "assignee_key" => $user->key
        ];

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => 'management']);
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }


    /**stage is assigned when all request data is correct*/
    public function testStageAssignedWhenAllRequestDataIsCorrect()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        factory(CodingChallenge::class, 10)->create(); //solve the error when stage type is code
        $payload = [
            "metadata" => [
                    "date" => "2021-03-25",
                    "time" => "12:08:28"
            ],
            "assignee_key" => $user->key
        ];

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            "success",
            "message",
            "data"
        ]);
    }

    /**assignee key required field validation is working*/
    public function testRequiredFieldValidationForAssigneeKeyIsWorking()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        $payload = [
            "status" => "started",
            "metadata" => [
                    "date" => "2021-03-25",
                    "time" => "12:08:28"
            ],
        ];

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**error shown when tried to access API using wrong candidacy key*/
    public function testNotAbleToAccessApiWithWrongCandidacyKey()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        $payload = [
            "status" => "started",
            "metadata" => [
                    "date" => "2021-03-25",
                    "time" => "12:08:28"
            ],
            "assignee_key" => $user->key
        ];

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->id . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**internal error when assignee key is wrong*/
    public function testInternalErrorIsShownWhenAssigneeKeyIsWrong()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        $payload = [
            "status" => "started",
            "metadata" => [
                    "date" => "2021-03-25",
                    "time" => "12:08:28"
            ],
            "assignee_key" => 1231
        ];

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**Stage is not assigned to inactive candidacy */
    public function testStageNotAssignedToInactiveCandidacy()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "inactive"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        $payload = [
            "status" => "started",
            "metadata" => [
                    "date" => "2021-03-25",
                    "time" => "12:08:28"
            ],
            "assignee_key" => $user->key
        ];

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertSee("Candidacy is inactive");
    }

    /* error shown when no questionnaires are there  */
    public function testErrorIsShownWhenQuestionnaireWhithGivenUrlNotExists()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create(["type" => "questionnaire"]);
        $payload = [
            "assignee_key" => $user->key
        ];

        //before there is no questionnaires
        $this->assertCount(0, Questionnaire::all());

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /* error shown when no questionnaire key set in stage metadata  */
    public function testGivesErrorWhenQuestionnaireKeyNotSetInStageMetadata()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $questionnaire = factory(Questionnaire::class)->create(["metadata" => ["url" => "https://example.url.com/form1"]]);
        $stage = factory(Stage::class)->create(["type" => "questionnaire"]);
        $payload = [
            "assignee_key" => $user->key
        ];

        $this->assertTrue(Stage::where('metadata->questionnaire_key', $questionnaire->key)->first() == null);
        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /* existing questionnaire is assigned if exists  */
    public function testQuestionnaireExistsThenItIsAssigned()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $questionnaire = factory(Questionnaire::class)->create(["metadata" => ["url" => "https://example.url.com/form1"]]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create(["type" => "questionnaire","metadata" => ["questionnaire_key" => $questionnaire->key]]);
        $payload = [
            "assignee_key" => $user->key
        ];


        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_CREATED);
    }


    /**................................candidacy close test cases...............................*/

    /** Candidacy should not closed by normal user */
    public function testCandidacyNotCloseByNormalUser()
    {
        $candidacy = factory(Candidacy::class)->create(['final_status' => "active"]);
        $user = factory(User::class)->create();
        $payload = [
            "candidacy_closing_reason" => "A sample reason for test",
            "actor_key" => $user->key
        ];
        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/close', $payload, ['group' => 'management']);
        $candidacy->refresh();

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**Candidacy close API stores reason as provided in request*/
    public function testCandidacyCloseApiStoresReasonSuccessfully()
    {
        $candidacy = factory(Candidacy::class)->create(['final_status' => "active"]);
        $user = factory(User::class)->create();
        $payload = [
            "candidacy_closing_reason" => "A sample reason for test",
            "actor_key" => $user->key
        ];
        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/close', $payload, ['group' => config("ldap.admin")]);
        $candidacy->refresh();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([$candidacy->key]);
        $this->assertTrue("A sample reason for test" == $candidacy->metadata["closing"]["reason"]);
    }

    /**Candidacy close API stores default reason when no reason given*/
    public function testCandidacyCloseApiStoresDefaultReasonSuccessfully()
    {

        $candidacy = factory(Candidacy::class)->create(['final_status' => "active"]);
        $user = factory(User::class)->create();
        $payload = [
        ];
        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/close', $payload, ['group' => config("ldap.admin")]);
        $candidacy->refresh();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([$candidacy->key]);
        $this->assertTrue("Reason not provided." == $candidacy->metadata["closing"]["reason"]);
    }

    /**Error when inactivating already inactive candidacy */
    public function testErrorOnClosingAlreadyInactiveCandidacy()
    {
        $candidacy = factory(Candidacy::class)->create(['final_status' => "inactive"]);
        $user = factory(User::class)->create();
        $payload = [
        ];
        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/close', $payload, ['group' => config("ldap.admin")]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertSee("Candidacy is already inactive");
    }

    /**Error given when user key is wrong*/
    public function testErrorIsShownWhenAssigneeKeyIsWrong()
    {
        $candidacy = factory(Candidacy::class)->create(['final_status' => "inactive"]);
        $user = factory(User::class)->create();
        $payload = [
        ];
        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/close', $payload, ['group' => config("ldap.admin")]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }



    /**................................stage close test cases...............................*/

    /** Stage closed by only assigned user */
    public function testStageClosedByAssignedUser()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        factory(CodingChallenge::class, 10)->create();

        $payload = [
            "stage_name" => $stage->name,
            "assignee_key" =>  $user->key #'Z4M617D'
        ];

        $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        unset($payload["assignee_key"]);

        $response = $this->json('post', '/api/v1/candidacies/' . $candidacy->key . '/closeStage', $payload, ['group' => 'management']);
        $candidacy->refresh();


        $stage = factory(Stage::class)->create();
        // Called by $this->json() user
        $payload = [
            "stage_name" => $stage->name,
            "assignee_key" =>  'Z4M617D'
        ];

        $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        unset($payload["assignee_key"]);


        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    /**stage name validation is working*/
    public function testStageNameValidationIsWorking()
    {
        $payload = [];
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/closeStage', $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**stage is closed when given proper request*/
    public function testStageIsClosedWhenRequestIsCorrect()
    {
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        $candidacy = factory(Candidacy::class)
        ->create(['final_status' => "active","metadata" => [
            "stages" => [
                Str::snake($stage->name) => [
                    "metadata" => [
                        "assignee_key" => $user->key
                    ]
                ]
            ]
        ]]);

        //dd($candidacy);
        factory(CodingChallenge::class, 10)->create();
        $payload = [
            "stage_name" => $stage->name,
            "assignee_key" => $user->key
        ];

        $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        unset($payload["assignee_key"]);

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/closeStage', $payload, ['group' => 'apricot_admins']);

        $candidacy->refresh();


        $response->assertStatus(Response::HTTP_OK);
      //  $this->assertTrue($candidacy->metadata["stages"][Str::snake($stage->name)]["status"] === "completed");
    }


    /**when there is no stage, it can not be closed*/
    public function testStageCanNotBeClosedWhichIsNotStartedYet()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $stage = factory(Stage::class)->create();
        $payload = [
            "stage_name" => $stage->name,
        ];

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/closeStage', $payload, ['group' => config("ldap.admin")]);
        $candidacy->refresh();

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertSee("Stage is not created yet");
    }

    public function testItShouldNotReAssignedNotClosedStage()
    {
        $candidacy = factory(Candidacy::class)->create(["final_status" => "active"]);
        $user = factory(User::class)->create();
        $stage = factory(Stage::class)->create();
        factory(CodingChallenge::class, 10)->create(); //solve the error when stage type is code
        $payload = [
            "metadata" => [
                    "date" => "2021-03-25",
                    "time" => "12:08:28"
            ],
            "assignee_key" => $user->key
        ];

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            "success",
            "message",
            "data"
        ]);

        $response = $this->json('post', '/api/v1/admin/candidacies/' . $candidacy->key . '/assignStage/' . $stage->key, $payload, ['group' => config("ldap.admin")]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson(['success' => false])->assertJsonStructure([
            "success",
            "message",
            "error"
        ]);
    }
}
