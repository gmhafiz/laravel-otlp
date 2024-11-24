<?php

namespace App\Jobs;

use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Instrumentation\CachedInstrumentation;
use OpenTelemetry\API\Trace\Span;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\Context\Context;
use OpenTelemetry\Contrib\Instrumentation\Guzzle\HeadersPropagator;
use OpenTelemetry\SemConv\TraceAttributes;

trait TraceJobs
{
//    private \OpenTelemetry\Context\ContextInterface $context;

    public \OpenTelemetry\Context\ContextKeyInterface $key;
//    private \OpenTelemetry\API\Trace\TracerInterface $tracer;
    private string $traceID;

    private string $spanID;

    public function __construct()
    {
        $this->setTrace();
    }

    public function setTrace()
    {

        $instrumentation = new CachedInstrumentation('io.opentelemetry.contrib.php.jobs', schemaUrl: TraceAttributes::SCHEMA_URL);
        $propagator = Globals::propagator();
        $parentContext = Context::getCurrent();
        $spanBuilder = $instrumentation
            ->tracer()
            ->spanBuilder(sprintf('job'))
            ->setParent($parentContext)
            ->setSpanKind(SpanKind::KIND_CONSUMER)
        ;
        $span = $spanBuilder->startSpan();
        $context = $span->storeInContext($parentContext);
        $propagator->inject($request, HeadersPropagator::instance(), $context);
        Context::storage()->attach($context);


//        $tracer = Globals::tracerProvider()->getTracer('');
//        \Illuminate\Support\Facades\Context::add('tracer', $tracer);


        $key = Context::createKey('traceID');
        $this->key = $key;
//        $contextWithRequestId = Context::getCurrent();
//        $scope = $contextWithRequestId->activate();
//        $context = $contextWithRequestId->with('traceID', '123456');

        $context ??= Context::getCurrent();
//        $this->context = $context;
//        $this->tracer = Globals::tracerProvider()->getTracer('');



        $spanContext = Span::fromContext($context)->getContext();


        $this->traceID = $spanContext->getTraceId();
        $this->spanID = $spanContext->getSpanId();

        \Illuminate\Support\Facades\Context::add('traceID', $this->traceID);
        \Illuminate\Support\Facades\Context::add('spanID', $this->spanID);

//        $scope = $context->activate();
//        $ctx = $context->with($key, $this->traceID);

        // TODO: Implement setTrace() method.
    }

    public function getTrace()
    {
        //        $tracer = Globals::tracerProvider()->getTracer('');
        return $this->traceID;

        // TODO: Implement getTrace() method.
    }

    public function getSpan()
    {
        return $this->spanID;
    }

}
