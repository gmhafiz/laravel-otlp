<?php

namespace App\Services;

use OpenTelemetry\API\Metrics\HistogramInterface;
use OpenTelemetry\SDK\Metrics\MeterProviderFactory;
use OpenTelemetry\SDK\Metrics\MeterProviderInterface;

class PrometheusService
{
    public MeterProviderInterface $meterProvider;
    public HistogramInterface $histogram;

    public function __construct()
    {
        $meterFactory = new MeterProviderFactory();
        $this->meterProvider = $meterFactory->create();
        $meter = $this->meterProvider->getMeter('meter');
        $this->histogram = $meter->createHistogram(
            'http_server_duration',
            'seconds',
            'latency histogram grouped by path in seconds'
        );
    }
}
