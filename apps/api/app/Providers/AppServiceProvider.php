<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\Contracts\AiLlmInterface::class,
            \App\Services\OpenAiLlmService::class
        );

        $this->app->bind(
            \App\Contracts\LlmGatewayInterface::class,
            \App\Services\LlmGateway::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
