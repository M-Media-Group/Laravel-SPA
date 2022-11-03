<?php

namespace Mmedia\LaravelFortifySanctumSpa;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Laravel\Fortify\Fortify;

class LaravelFortifySanctumSpaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-spa');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-spa');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-spa.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-spa'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-spa'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-spa'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }

        $this->setCorsOptions();
        $this->setFortifyViewsToFalse();

        RateLimiter::for(config('laravel-spa.route_paths.email_exists'), function (Request $request) {
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });

        $this->setupFortifyViews();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-spa');

        // Register the main class to use with the facade
        // $this->app->singleton('laravel-spa', function () {
        //     return new LaravelFortifySanctumSpa;
        // });
    }

    /**
     * Set the CORS options on the routes
     *
     * @return void
     */
    private function setCorsOptions()
    {
        $this->addPathsToCors();
        $this->addAllowedOriginsToCors();
        $this->setCorsSupportsCredentials();
    }

    /**
     * Add the routes to CORS
     *
     * @return void
     */
    private function addPathsToCors()
    {
        // The current paths in the config
        $paths = $this->app['config']->get('cors.paths');

        $additionalPaths = [
            'sanctum/csrf-cookie',
            'register',
            'login',
            'logout',
            'email/verification-notification',
            'forgot-password',
            'reset-password',
            'user/confirm-password',
            'two-factor-challenge',
            'user/two-factor-authentication',
            'user/confirmed-two-factor-authentication',
            'user/two-factor-recovery-codes',
            'user/profile-information'
        ];

        if (config('laravel-spa.check_email_exists_endpoint')) {
            $additionalPaths[] = config('laravel-spa.route_paths.email_exists');
        }

        $paths = array_merge($paths, $additionalPaths);

        // Set the new config paths
        config(['cors.paths' => $paths]);
    }

    /**
     * Add the SPA origins to CORS
     *
     * @return void
     */
    private function addAllowedOriginsToCors()
    {
        // The current allowed origins in the config
        $allowedOrigins = $this->app['config']->get('cors.allowed_origins');

        $additionalAllowedOrigins = [config('app.url'), config('laravel-spa.spa_url')];

        $allowedOrigins = array_merge($allowedOrigins, $additionalAllowedOrigins);

        // Set the new config allowed origins
        config(['cors.allowed_origins' => $allowedOrigins]);
    }

    /**
     * Set the CORS supports credentials to true
     *
     * @return void
     */
    private function setCorsSupportsCredentials()
    {
        config(['cors.supports_credentials' => true]);
    }

    /**
     * Force the Fortify views config option to be false
     *
     * @return void
     */
    private function setFortifyViewsToFalse()
    {
        config(['fortify.views' => false]);
    }

    /**
     * Set up the Fortify views. Even though we force the views to be off, sometimes these are required (for example when emails are being sent by Fortify)
     *
     * @return void
     */
    private function setupFortifyViews()
    {
        $spa_url = config('laravel-spa.spa_url');

        Fortify::loginView(function () use ($spa_url) {
            return redirect($spa_url . '/' . config('laravel-spa.route_paths.login'));
        });

        Fortify::registerView(function () use ($spa_url) {
            return redirect($spa_url . '/' . config('laravel-spa.route_paths.register'));
        });

        Fortify::twoFactorChallengeView(function () use ($spa_url) {
            return redirect($spa_url . '/' . config('laravel-spa.route_paths.two_factor_challenge'));
        });

        Fortify::requestPasswordResetLinkView(function () use ($spa_url) {
            return redirect($spa_url . '/' . config('laravel-spa.route_paths.forgot_password'));
        });

        Fortify::resetPasswordView(function ($request) use ($spa_url) {
            return redirect($spa_url . '/' . config('laravel-spa.route_paths.reset_password') . '?token=' . $request->route('token'));
        });

        Fortify::verifyEmailView(function () use ($spa_url) {
            return redirect($spa_url . '/' . config('laravel-spa.route_paths.verify_email'));
        });

        Fortify::confirmPasswordView(function () use ($spa_url) {
            return redirect($spa_url . '/' . config('laravel-spa.route_paths.confirm_password'));
        });
    }
}
