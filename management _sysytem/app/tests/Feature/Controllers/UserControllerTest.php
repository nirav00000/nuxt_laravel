<?php

namespace Tests\Feature\Controllers;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**fetch all users*/
    public function testFetchListOfUsersFromDatabaseInCorrectFormat()
    {

        $user = factory(User::class, 10)->create();

        $response = $this->json('get', '/api/v1/users');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'success',
                'message',
                'data',
            ]
        );
        $response->assertJsonFragment(['key' => $user->first()->key]);
        $response->assertJsonFragment(['name' => $user->first()->name]);
        $response->assertJsonFragment(['email' => $user->first()->email]);
    }
}
