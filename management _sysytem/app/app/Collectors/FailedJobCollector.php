<?php

namespace App\Collectors;

use Illuminate\Support\Facades\DB;
use Superbalist\LaravelPrometheusExporter\CollectorInterface;
use Superbalist\LaravelPrometheusExporter\PrometheusExporter;

class FailedJobCollector implements CollectorInterface
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
        return 'failed-jobs';
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
        $this->gauge = $exporter->registerGauge('failed_jobs_total', 'The total number of failed jobs in telmetrics.', ['env']);
    }


     /**
      * Collect metrics data, if need be, before exporting.
      *
      * As an example, this may be used to perform time consuming database queries and set the value of a counter
      * or gauge.
      */
    public function collect()
    {
        $this->gauge->set(DB::table('failed_jobs')->count(), [app()->environment()]);
    }
}
