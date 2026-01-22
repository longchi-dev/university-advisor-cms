<?php

namespace App\Providers;

use App\Contracts\Repositories\IGamingSessionRepository;
use App\Contracts\Repositories\IKeywordLabelRepository;
use App\Contracts\Repositories\IKeywordRepository;
use App\Contracts\Repositories\IOutcomeImageRepository;
use App\Contracts\Repositories\IPlayerRepository;
use App\Contracts\Repositories\IUploadImageRepository;
use App\Models\User;
use App\Repositories\GamingSessionRepository;
use App\Repositories\KeywordLabelRepository;
use App\Repositories\KeywordRepository;
use App\Repositories\OutcomeImageRepository;
use App\Repositories\PlayerRepository;
use App\Repositories\UploadImageRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IGamingSessionRepository::class, GamingSessionRepository::class);
        $this->app->bind(IPlayerRepository::class, PlayerRepository::class);
        $this->app->bind(IKeywordRepository::class, KeywordRepository::class);
        $this->app->bind(IKeywordLabelRepository::class, KeywordLabelRepository::class);
        $this->app->bind(IUploadImageRepository::class, UploadImageRepository::class);
        $this->app->bind(IOutcomeImageRepository::class, OutcomeImageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
//        $this->registerPolicies();
    }
}
