<?php

namespace App\Jobs;

use OpenTelemetry\API\Trace\Span;
use OpenTelemetry\Context\Context;

class TraceJobs2
{
    private string $traceID;

    private string $spanID;

    public function __construct()
    {
        $context ??= Context::getCurrent();

        //        $this->tracer = Globals::tracerProvider()->getTracer('');

        $spanContext = Span::fromContext($context)->getContext();
        $this->traceID = $spanContext->getTraceId();
        $this->spanID = $spanContext->getSpanId();
    }
}
