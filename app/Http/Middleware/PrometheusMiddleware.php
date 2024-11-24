<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Prometheus\CollectorRegistry;
use Prometheus\Exception\MetricNotFoundException;
use Prometheus\Exception\MetricsRegistrationException;
use Prometheus\Exception\StorageException;
use Prometheus\Histogram;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMiddleware
{
    private CollectorRegistry $registry;

    public function __construct(CollectorRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $EXCLUDE_PATH = config('opentelemetry.php_excluded_urls');

        $uri = $request->getRequestUri();

        if (in_array($uri, $EXCLUDE_PATH)) {
            return $next($request);
        }

        $startTime = defined('LARAVEL_START') ? LARAVEL_START : $request->server('REQUEST_TIME_FLOAT');

        $result = $next($request);

        $duration = $startTime ? (microtime(true) - $startTime) : null;
        $duration = $duration * 1000;

        $histogram = null;
        $method = $request->method();
        $uri = $request->route()->uri();

        try {
            $registry = $this->registry;

            $histogram = $registry->getOrRegisterHistogram(
                'http_server_duration',
                'milliseconds',
                'latency histogram grouped by path in milliseconds',
                ['job', 'method', 'path'],
                Histogram::exponentialBuckets(1, 2, 15),
            );

        } catch (MetricNotFoundException $e) {
        } catch (MetricsRegistrationException $e) {
        } catch (StorageException $e) {
        } catch (GuzzleException $e) {
        } catch (\Exception $e) {

        } finally {
            $histogram->observe($duration, ['Laravel', $method, $uri]);

            return $result;
        }
    }
}
