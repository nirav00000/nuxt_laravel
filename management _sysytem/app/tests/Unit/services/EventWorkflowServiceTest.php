<?php

namespace Tests\Unit\Services;

use App\Jobs\User\SendWelcomeEmail;
use App\Jobs\DeleteUser;
use App\Jobs\RestoreUser;
use App\Jobs\UpdateUser;
use App\Services\EventWorkflowService;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EventWorkflowServiceTest extends TestCase
{
    use WithFaker;


    public function testUserWasCreated()
    {
        // Event::fake();
        Queue::fake();

        $service = new EventWorkflowService();

        // Make User
        $user = factory(User::class)->make();

        $payload = [
            'event' => 'laravel-api.user-was-created',
            'data'  => [
                'user' => $user->toArray(),
            ],
        ];
        $results = $service->process($payload);
        self::assertTrue($results['success']);
        Queue::assertPushed(
            SendWelcomeEmail::class,
            function ($job) use ($user) {

                return $job->payload['name'] === $user->name;
            }
        );
    }
}
