<?php

namespace App\Jobs;

use App\Http\Repositories\SendEmailRepositories;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use OpenTelemetry\Context\Context;

class SendEmail extends TraceJobs2 implements ShouldQueue, Trace
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, TraceJobs;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $traceID = $this->getTrace();
        $spanID = $this->getSpan();

        $tracer = \OpenTelemetry\API\Globals::tracerProvider()->getTracer('');

        $contextWithRequestId1 = Context::getCurrent()->with($this->key, $traceID);
        $scope = $contextWithRequestId1->activate();

        $root = $tracer->spanBuilder('in the job handler')
            ->setParent($contextWithRequestId1)
            ->setAttribute('request.id', $contextWithRequestId1->get($this->key))
            ->startSpan();

        try {
            (new SendEmailRepositories())->send();
        } catch (\Exception $e) {
            $root->recordException($e);
        } finally {
            $scope->detach();
            $root->end();
        }
    }
}
