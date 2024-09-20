<?php

namespace Roomies\Fraudable;

use Aws\FraudDetector\FraudDetectorClient;
use Illuminate\Support\ServiceProvider;

class FraudableServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/fraudable.php', 'fraudable'
        );

        $this->app->bind(FraudDetectorClient::class, function ($app) {
            return new FraudDetectorClient([
                'version' => 'latest',
                'region' => $app['config']['fraudable.aws.region'],
                'credentials' => [
                    'key' => $app['config']['fraudable.aws.key'],
                    'secret' => $app['config']['fraudable.aws.secret'],
                ],
            ]);
        });
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
            __DIR__.'/../config/fraudable.php' => config_path('fraudable.php'),
        ], 'fraudable');
    }
}
