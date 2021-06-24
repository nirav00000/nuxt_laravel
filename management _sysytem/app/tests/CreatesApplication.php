<?php

namespace Tests;

use App\User;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{


    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }


    public function setUp(): void
    {

        parent::setUp();

        $user = factory(User::class)->create();

        $this->actingAs($user);
    }
}
