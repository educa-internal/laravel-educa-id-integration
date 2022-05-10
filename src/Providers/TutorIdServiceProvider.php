<?php

namespace Tutor\Id\Providers;

use Illuminate\Support\ServiceProvider;
use Tutor\Id\Services\Contracts\UserServiceInterface;
use Tutor\Id\Services\Socialite\SocialiteTutorIdProvider;
use Tutor\Id\Services\UserService;

class TutorIdServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->bootSocialiteTutorId();
        $this->bootRoute();

        $this->loadTranslation();
    }

    private function bootSocialiteTutorId()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            config('services.tutor-id.diver_name'),
            function ($app) use ($socialite) {
                $config = $app['config']['services.tutor-id'];
                return $socialite->buildProvider(SocialiteTutorIdProvider::class, $config);
            }
        );
    }

    private function bootRoute()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }

    private function loadTranslation()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'tutor_id');
    }

    public function register()
    {
        $this->mergeConfig();
    }

    private function mergeConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/services.php', 'services');
    }
}
