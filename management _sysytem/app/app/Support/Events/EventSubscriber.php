<?php

namespace App\Support\Events;

use App\Services\EventPublishService;
use App\Support\Events\ShouldPublish;

final class EventSubscriber
{


    public function __construct()
    {
    }


    public function subscribe($events): void
    {
        $events->listen('*', static::class . '@handle');
    }


    public function handle(string $eventName, array $payload): void
    {
        if ($this->shouldBePublished($eventName) === false) {
            // dump('not publishing event ' . $eventName);
            return;
        }

        // Publish event
        // Send Event to SNS - event is in index 0
        app(EventPublishService::class, ['event' => $payload[0]])->send();
    }


    private function shouldBePublished($event): bool
    {
        if (class_exists($event) === false) {
            return false;
        }

        return is_subclass_of($event, ShouldPublish::class);
    }
}
