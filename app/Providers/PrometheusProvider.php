<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\APCng;
use Prometheus\Storage\InMemory;
use Prometheus\Storage\Redis;


class PrometheusProvider extends ServiceProvider
{

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CollectorRegistry::class, static function () {
//            $redis = new Redis([
//                'host' => config('database.redis.default.host') ?? 'localhost',
//                'port' => config('database.redis.default.port') ?? 6379,
//                'password' => config('database.redis.default.password') ?? null,
//                'timeout' => 0.1, // in seconds
//                'read_timeout' => '10', // in seconds
//                'persistent_connections' => config('database.redis.default.persistent', false),
//            ]);
//
//            return new CollectorRegistry($redis);
            return new CollectorRegistry(new InMemory());
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
