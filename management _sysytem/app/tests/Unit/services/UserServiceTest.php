<?php

namespace Tests\Unit\Services;

use App;
use App\Services\UserService;
use App\User;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserServiceTest extends TestCase
{


    public function testCreateUser()
    {
        $input = factory(\App\User::class)->make();
        // dd($input);
        $inputData = [
            'name'     => $input->name,
            'email'    => $input->email,
            'password' => $input->password,
            'api_key'  => Str::random(),
        ];

        $service = app(UserService::class);

        $this->assertEquals(true, $service->create($inputData));
    }


    public function testUpdateUser()
    {
        $user  = factory(App\User::class)->create();
        $input = factory(App\User::class)->make();

        $updateData = [
            'name'    => $input->name,
            'api_key' => Str::random(),
        ];

        $service = app(UserService::class);

        $this->assertEquals(true, $service->update($user, $updateData));
    }


    public function testDeleteUser()
    {
        $user  = factory(App\User::class)->create();
        $input = factory(App\User::class)->make();

        $service = app(UserService::class);

        $this->assertEquals(true, $service->delete($user));
    }


    public function testRestoreUser()
    {
        $user = factory(App\User::class)->create();
        $user->delete();
        // Delete user
        $service = app(UserService::class);

        $this->assertEquals(true, $service->restore($user));
    }
}
