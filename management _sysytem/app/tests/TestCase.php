<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;


    public function actingAs($user, $driver = null)
    {
        $token = FacadesJWTAuth::fromUser($user);
        $this->withHeader('X-AUTH-TOKEN', $token);
        parent::actingAs($user);

        return $this;
    }
}
