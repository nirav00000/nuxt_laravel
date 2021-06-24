<?php

namespace App\EventHandlers\LaravelApi;

use App\Jobs\User\SendWelcomeEmail;
use Illuminate\Support\Arr;

class UserWasCreatedEventHandler
{


    /**
     * Handle the event.
     *
     * @param  array $event
     * @return bool
     */
    public function handle(array $event): bool
    {
        info("UserWasCreatedEventHandler->handle() - Queueing up UserService->create()", ['payload' => $event]);

        // Dispatch Job
        SendWelcomeEmail::dispatch(Arr::get($event, 'data.user'))->onConnection('sqs')->onQueue(config('queue.queues.default'));
        return true;
    }
}
