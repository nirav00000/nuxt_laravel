<?php

namespace App\Collectors;

use App\User;
use Superbalist\LaravelPrometheusExporter\CollectorInterface;
use Superbalist\LaravelPrometheusExporter\PrometheusExporter;

class UserCollector implements CollectorInterface
{
    /**
     * @var mixed
     */
    protected $gauge;


     /**
      * Return the name of the collector.
      *
      * @return string
      */
    public function getName()
    {
        return 'users';
    }


     /**
      * Register all metrics associated with the collector.
      *
      * The metrics needs to be registered on the exporter object.
      * eg:
      * ```php
      * $exporter->registerCounter('search_requests_total', 'The total number of search requests.');
      * ```
      *
      * @param PrometheusExporter $exporter
      */
    public function registerMetrics(PrometheusExporter $exporter)
    {
        $this->gauge = $exporter->registerGauge('total_user', 'The total number of active Users in template.', ['status', 'env']);
    }


     /**
      * Collect metrics data, if need be, before exporting.
      *
      * As an example, this may be used to perform time consuming database queries and set the value of a counter
      * or gauge.
      */
    public function collect()
    {
        $this->gauge->set(User::count(), ['users', app()->environment()]);
    }
}
