<?php

namespace App\Logging;

use Illuminate\Support\Facades\Auth;
use Superbalist\Monolog\Formatter\GoogleCloudJsonFormatter;

class GoogleStackDriverFormatter
{


    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger $logger
     *
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new GoogleCloudJsonFormatter());

            // custom web processor with correlation id
            $webProcessor = new \Monolog\Processor\WebProcessor();
            $webProcessor->addExtraField('correlation-id', 'HTTP_X_LDE_REQUEST_ID');
            $webProcessor->addExtraField('kong-consumer-id', 'HTTP_X_CONSUMER_ID');
            $webProcessor->addExtraField('kong-consumer-custom-id', 'HTTP_X_CONSUMER_CUSTOM_ID');
            $webProcessor->addExtraField('kong-consumer-username', 'HTTP_X_CONSUMER_USERNAME');
            $handler->pushProcessor($webProcessor);

            $handler->pushProcessor(new \Monolog\Processor\UidProcessor(7));
            $handler->pushProcessor(new \Monolog\Processor\MemoryPeakUsageProcessor());
            $handler->pushProcessor(new \Monolog\Processor\MemoryUsageProcessor());
            $handler->pushProcessor(new \Monolog\Processor\ProcessIdProcessor());
            $handler->pushProcessor(new \Monolog\Processor\IntrospectionProcessor());

            // Add User Processor
            $handler->pushProcessor(new UserProcessor(Auth::user()));
        }
    }
}
