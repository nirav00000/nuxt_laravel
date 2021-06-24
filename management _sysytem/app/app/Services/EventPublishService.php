<?php

namespace App\Services;

use App;
use App\Exceptions\HelperException;
use App\Helpers\ExceptionHelper;
use Google\Cloud\PubSub\PubSubClient;
use Helper;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use ReflectionClass;

class EventPublishService
{
    /**
     * @var string
     */
    protected $event;


    public function __construct($event)
    {
        $this->event = $event;
    }


    /**
     * Method to check that service is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        try {
            // create sns client
            $sns        = app('aws')->createClient('sns');
            $attributes = $sns->getTopicAttributes(['TopicArn' => config('aws.event_arn')]);

            return Arr::get($attributes, '@metadata.statusCode', 0) === 200;
        } catch (\Exception $ex) {
            error('EventPublishService->isAvailable - Caught an exception', ['exception' => $ex->getMessage()]);

            return false;
        }
    }


    /**
     * Sends event to SNS
     *
     * @return bool
     * @throws \App\Exceptions\HelperException
     * @throws \ReflectionException
     */
    public function send()
    {
        // build the payload
        $payload = $this->buildPayload();
        // dd($payload);
        // Don't send events to SNS in testing
        if (App::environment('testing') === false && App::environment('local') === false) {
            $pubSub = new PubSubClient(config('gcp.pubsub'));

            // Get an instance of a previously created topic.
            $topic = $pubSub->topic(config('queue.pub.event'));

            // Publish a message to the topic.
            $response = $topic->publish(
                [
                    'data' => json_encode($payload),
                ]
            );

            debug(
                'EventPublishService->send() - Sent event.',
                [
                    'Message'  => json_encode($payload),
                    'response' => $response,
                    'Subject'  => 'New Event: ' . $payload['event'],
                ]
            );
        } else {
            debug(
                'EventPublishService->send() - Skipping event since we are testing environment.',
                [
                    'Message' => json_encode($payload),
                    'Subject' => 'New Event: ' . $payload['event'],
                ]
            );
        }//end if

        return true;
    }


    /**
     * Builds the event name by combining the app name and the event class name
     *
     * eg:thryv-leads.user-was-created"
     *
     * @param string $eventClass
     *
     * @return string
     * @throws \App\Exceptions\HelperException
     */
    public static function buildEventName($eventClass)
    {
        if (get_class($eventClass) !== false) {
            return Str::slug(config('app.name')) . '.' . kebab_case(class_basename($eventClass));
        } else {
            throw new HelperException('Cannot build event name. Not a valid class.');
        }
    }


    /**
     * Method to build the payload for sending to SNS
     *
     * @return array
     *
     * @throws \App\Exceptions\HelperException
     * @throws \ReflectionException
     */
    public function buildPayload()
    {
        $name = static::buildEventName($this->event);

        $data = [];

        // use reflection to get public properties
        $reflect = new ReflectionClass($this->event);

        // Add public properties to the payload
        foreach ($reflect->getProperties() as $property) {
            $key   = $property->getName();
            $value = $this->event->{$key};
            if (is_object($value) && get_class($value) === 'Exception') {
                $data[$key] = ExceptionHelper::toArray($value);
            } else {
                $data[$key] = $value;
            }
        }

        $payload = [
            'event'     => $name,
            'data'      => $data,
            'user'      => (Auth::check()) ? Auth::user() : null,
            'timestamp' => microtime(true),
        ];

        return $payload;
    }
}
