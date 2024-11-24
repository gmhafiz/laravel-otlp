<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\TracerInterface;

class UserRepositories
{
    /**
     * @throws \Exception
     */
    public function all(TracerInterface $tracer)
    {
        $root = $tracer->spanBuilder('all')->startSpan();
        $scope = $root->activate();

        // Adding an event is another way to add information to the span, without using Laravel's
        // logging system.
        $root->addEvent('entering all user repository', [
            'key' => 'value',
        ]);

        try {
            return $this->yetAnotherIndirection();
        } catch (\Exception $e) {
            $root->recordException($e);
            throw $e;
        } finally {
            $root->end();
            $scope->detach();
        }
    }

    /**
     * @throws \Exception
     */
    private function yetAnotherIndirection()
    {
        $tracerProvider = Globals::tracerProvider();
        $tracer = $tracerProvider->getTracer('');
        $root = $tracer->spanBuilder('yetAnotherIndirection')->startSpan();
        $scope = $root->activate();

        try {
            $root->addEvent('actual DB call is done here');

            $amount = mt_rand(1, 100);
            Log::info('random amount is ', (array) $amount);

            return DB::table('users')
                ->select(['id', 'name', 'email', 'email_verified_at', 'created_at', 'updated_at'])
                ->orderByDesc('id')
                ->limit($amount)
                ->get();
        } catch (\Exception $e) {
            $root->recordException($e);
            throw $e;
        } finally {
            $root->end();
            $scope->detach();
        }
    }
}
