<?php

namespace App\Helpers;

use Prometheus\Exception\MetricNotFoundException;
use Prometheus\Exception\MetricsRegistrationException;

class StatsHelper
{


    public static function incCounter($counter_name, $qty = 1, array $labels = [], $description = null, array $labelNames = [])
    {
        try {
            $exporter = app('prometheus');

            // Ensure labelNames have env
            if (in_array('env', $labelNames) === false) {
                $labelNames[] = 'env';
            }

            // Add env to $labels
            if (in_array(app()->environment(), $labels) === false) {
                $labels[] = app()->environment();
            }

            try {
                $counter = $exporter->getCounter($counter_name);
            } catch (MetricNotFoundException $ex) {
                $counter = $exporter->registerCounter($counter_name, $description, $labelNames);
            } catch (MetricsRegistrationException $ex) {
                $counter = $exporter->registerCounter($counter_name, $description, $labelNames);
            }

            if ($counter->getLabelNames()) {
                if (empty($labels) === false) {
                    $counter->incBy($qty, $labels);
                } else {
                    $counter->incBy($qty, ['none']);
                }
            } else {
                $counter->incBy($qty);
            }
        } catch (\Exception $ex) {
            error(
                "StatsHelper->incCounter() threw an exception!!",
                [
                    'error' => $ex->getMessage(),
                ]
            );
        }//end try
    }
}
