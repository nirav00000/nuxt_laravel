<?php

/**
 * List of plain SQS queues and their corresponding handling classes
 */

return [
    'handlers'        => [
        'test' => App\Jobs\ProcessSNSMessages::class,
    ],

    'default-handler' => App\Jobs\ProcessSNSMessages::class,
];
