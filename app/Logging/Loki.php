<?php

namespace App\Logging;

use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;
use OpenTelemetry\API\Globals;

class Loki
{
    public function __invoke(Logger $logger): void
    {
        $traceID = $this->getTraceID();

        foreach ($logger->getHandlers() as $handler) {
            if (config('logging.json_format')) {
                $handler->setFormatter(new CustomJson($traceID));
            } else {
                $handler->setFormatter(new LineFormatter(
                    '[%datetime%] %channel%.%level_name%: traceID='.$traceID." %message% %context% %extra%\n"
                ));
            }
        }
    }

    private function getTraceID(): string
    {
        $tracer = Globals::tracerProvider()->getTracer('');
        $root = $tracer->spanBuilder('')->startSpan();

        return $root->getContext()->getTraceId();
    }
}
