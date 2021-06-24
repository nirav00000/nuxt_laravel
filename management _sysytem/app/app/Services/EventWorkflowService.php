<?php

namespace App\Services;

use App;
use Illuminate\Support\Arr;

class EventWorkflowService
{


    /**
     * @param array $payload
     *
     * @return array
     */
    public function process(array $payload)
    {
        try {
            $event     = Arr::get($payload, 'event');
            $className = $this->inferEventHandlersClassName($event);

            info("EventWorkflowService->process() - Finding action for '{$event}'", ['payload' => $payload, 'class_name' => $className]);
            try {
                info("EventWorkflowService->process() - Handler class identified as '{$event}'", ['payload' => $payload, 'class_name' => $className]);

                if (class_exists($className)) {
                    // Handle Event
                    $return = app()->make($className)->handle($payload);

                    // log stat to Prometheus
                    App\Helpers\StatsHelper::incCounter('events_total', 1, [$event, 'processed'], "Total number of SNS events processed.", ['name', 'status']);

                    return ['success' => $return];
                } else {
                    info("EventWorkflowService->process() - No handler class found for '{$event}'. Ignoring", ['payload' => $payload, 'class_name' => $className]);
                    // dump('No handler class found');
                    // log stat to Prometheus
                    App\Helpers\StatsHelper::incCounter('events_total', 1, [$event, 'skipped'], "Total number of SNS events processed.", ['name', 'status']);

                    // Return true since this is not an error
                    return ['success' => true];
                }
            } catch (\Exception $ex) {
                error(
                    "EventWorkflowService->process() - Exception thrown in {$className}",
                    [
                        'error'      => $ex->getMessage(),
                        'file'       => $ex->getFile(),
                        'line'       => $ex->getLine(),
                        'class_name' => $className,
                        'payload'    => $payload,
                    ]
                );

                // log stat to Prometheus
                App\Helpers\StatsHelper::incCounter('events_total', 1, [$event, 'error'], "Total number of SNS events processed.", ['name', 'status']);

                return ['success' => false, 'error' => $ex->getMessage()];
            }//end try
        } catch (\Exception $ex) {
            error(
                "EventWorkflowService->process() - Threw an exception!!",
                [
                    'error'   => $ex->getMessage(),
                    'file'    => $ex->getFile(),
                    'line'    => $ex->getLine(),
                    'payload' => $payload,
                ]
            );

            // log stat to Prometheus
            App\Helpers\StatsHelper::incCounter('events_total', 1, [$event, 'error'], "Total number of SNS events processed.", ['name', 'status']);

            return ['success' => false, 'error' => $ex->getMessage()];
        }//end try
    }


    public function inferEventHandlersClassName($eventName)
    {
        $class = [];
        $parts = explode(".", $eventName);

        foreach ($parts as $part) {
            $class[] = str_replace(' ', '', ucwords(str_replace('-', ' ', $part)));
        }

        return 'App\\EventHandlers\\' . implode("\\", $class) . 'EventHandler';
    }
}
