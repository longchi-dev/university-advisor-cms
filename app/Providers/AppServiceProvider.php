<?php

namespace App\Providers;

use App\AIClients\Ollama\OllamaNgrokClient;
use App\Contracts\AIClients\ILlmClient;
use App\Contracts\Repositories\IUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(ILlmClient::class, OllamaNgrokClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        $this->registerPolicies();
    }
}
