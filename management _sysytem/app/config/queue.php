<?php

return [

    /*
        |--------------------------------------------------------------------------
        | Default Queue Connection Name
        |--------------------------------------------------------------------------
        |
        | Laravel's queue API supports an assortment of back-ends via a single
        | API, giving you convenient access to each back-end using the same
        | syntax for every one. Here you may define a default connection.
        |
    */

    'default'     => env('QUEUE_CONNECTION', 'sync'),

    /*
        |--------------------------------------------------------------------------
        | Queue Connections
        |--------------------------------------------------------------------------
        |
        | Here you may configure the connection information for each server that
        | is used by your application. A default configuration has been added
        | for each back-end shipped with Laravel. You are free to add more.
        |
        | Drivers: "sync", "database", "beanstalkd", "sqs", "redis", "null"
        |
    */

    'connections' => [

        'sync'       => [
            'driver' => 'sync',
        ],

        'database'   => [
            'driver'      => 'database',
            'table'       => 'jobs',
            'queue'       => 'default',
            'retry_after' => 90,
        ],

        'beanstalkd' => [
            'driver'      => 'beanstalkd',
            'host'        => 'localhost',
            'queue'       => 'default',
            'retry_after' => 90,
            'block_for'   => 0,
        ],

        'sqs'        => [
            'driver' => 'sqs',
            'key'    => env('AWS_ACCESS_KEY_ID', 'your-public-key'),
            'secret' => env('AWS_SECRET_ACCESS_KEY', 'your-secret-key'),
            'prefix' => env('SQS_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue'  => env('SQS_QUEUE', 'your-queue-name'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        ],

        'sns-events' => [
            'driver' => 'sqs-plain',
            'key'    => env('SNS_EVENTS_SQS_KEY', ''),
            'secret' => env('SNS_EVENTS_SQS_SECRET', ''),
            'prefix' => env('SNS_EVENTS_SQS_PREFIX', 'https://sqs.us-east-2.amazonaws.com/501388824769'),
            'queue'  => env('SNS_EVENTS_SQS_QUEUE', ''),
            'region' => env('SNS_EVENTS_SQS_REGION', 'us-east-1'),
        ],

        'redis'      => [
            'driver'      => 'redis',
            'connection'  => 'default',
            'queue'       => 'default',
            'retry_after' => 90,
            'block_for'   => null,
        ],

        'pubsub'     => [
            'driver'          => 'pubsub',
            'queue'           => env('JOB_PUB'),
            'project_id'      => env('PUBSUB_PROJECT_ID', 'your-project-id'),
            'retries'         => 5,
            'request_timeout' => 60,
            'keyFilePath'     => env('PUBSUB_KEY_FILE', 'file-path'),
            // Subscriber will be key and publisher will be value here
            'subscribers'     => [
                env('EVENTS_SUB') => env('EVENTS_PUB'),
                env('JOB_SUB')    => env('JOB_PUB'),
            ],
        ],

    ],

    /*
        |--------------------------------------------------------------------------
        | Job Queue Jobs
        |--------------------------------------------------------------------------
        |
        | A dedicated job queue for each of the main actions
        |
    */

    'queues'      => [
        'default' => env('QUEUE_DEFAULT'),
    ],

    /*
        |--------------------------------------------------------------------------
        | Job Publishers
        |--------------------------------------------------------------------------
        |
        | A dedicated job publisher for each of the main actions
        |
    */

    'subscribers' => [
        'event'   => env('EVENTS_SUB'),
        'default' => env('JOB_SUB'),
    ],

    'pub'         => [
        'event'   => env('EVENTS_PUB'),
        'default' => env('JOB_PUB'),
    ],

    /*
        |--------------------------------------------------------------------------
        | Failed Queue Jobs
        |--------------------------------------------------------------------------
        |
        | These options configure the behavior of failed queue job logging so you
        | can control which database and table are used to store the jobs that
        | have failed. You may change them to any database / table you wish.
        |
    */

    'failed'      => [
        'database' => env('DB_CONNECTION', 'mysql'),
        'table'    => 'failed_jobs',
    ],

];
