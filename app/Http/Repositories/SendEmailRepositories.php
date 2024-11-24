<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\Log;
use OpenTelemetry\API\Globals;

class SendEmailRepositories {
    public function send()
    {
        $tracer = Globals::tracerProvider()->getTracer('');

        $root = $tracer->spanBuilder('sends email')->startSpan();
        $scope = $root->activate();

        Log::info('email sent');

        $scope->detach();
        $root->end();
    }
}
