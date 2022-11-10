<?php

namespace Mmedia\LaravelSpa;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Laravel\Fortify\Fortify;
use Mmedia\LaravelSpa\Http\Middleware\SetLocale;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LaravelSpaServiceProvider extends ServiceProvider
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

        if (!app()->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__ . '/routes.php');
        }

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

        RateLimiter::for(config('laravel-spa.route_paths.email_exists'), function (Request $request) {
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });

        $this->setupFortifyViews();

        // Register the SetLocale middleware globally in the kernel
        $this->app->make(\Illuminate\Contracts\Http\Kernel::class)
            ->pushMiddleware(SetLocale::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        if (!app()->configurationIsCached()) {
            // Automatically apply the package configuration
            $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-spa');
        }

        // Register the main class to use with the facade
        $this->app->singleton(LaravelSpa::class, function () {
            return new LaravelSpa;
        });

        if (!app()->configurationIsCached()) {
            $this->setCorsOptions();
            $this->setFortifyViewsToTrue();
            $this->setFortifyHomeToSpaUrl();
        }
        $this->redirectNotFoundToSpa();
    }

    /**
     * Redirect any non API 404 exceptions to the SPA
     *
     * @return void
     */
    private function redirectNotFoundToSpa()
    {
        $errorHandler = $this->app->make(ExceptionHandler::class);
        $errorHandler->renderable(function (NotFoundHttpException $e, $request) {
            if (!$request->is('api/*')) {
                return redirect()->to(config('laravel-spa.spa_url') . $request->getRequestUri());
            }
        });
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
            'user/confirmed-password-status',
            'user/profile-information',
            'oauth/personal-access-tokens'
        ];

        if (config('laravel-spa.check_email_exists_endpoint')) {
            $path = config('laravel-spa.route_paths.email_exists');
            // Replace any {variable:slug} with *
            $path = preg_replace('/\{.*?\}/', '*', $path);
            $additionalPaths[] = $path;
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
     * Force the Fortify views config option to be true
     *
     * @return void
     */
    private function setFortifyViewsToTrue()
    {
        config(['fortify.views' => true]);
    }

    /**
     * Force the value of the Fortify home option to start with the SPA URL if its not already a URL
     *
     * @return void
     */
    private function setFortifyHomeToSpaUrl()
    {
        $currentPath = config('fortify.home');
        // If the currentPath already starts with a URL, don't change it
        if (preg_match('/^https?:\/\//', $currentPath)) {
            return;
        }
        config(['fortify.home' => config('laravel-spa.spa_url') . $currentPath]);
    }

    /**
     * Set up the Fortify view redirects
     *
     * @return void
     */
    private function setupFortifyViews()
    {
        Fortify::loginView(function () {
            return redirect(LaravelSpaFacade::getSpaUrlForPath('login'));
        });

        Fortify::registerView(function () {
            return redirect(LaravelSpaFacade::getSpaUrlForPath('register'));
        });

        Fortify::twoFactorChallengeView(function () {
            return redirect(
                LaravelSpaFacade::getSpaUrlForPath('two_factor_challenge')
            );
        });

        Fortify::requestPasswordResetLinkView(function () {
            return redirect(
                LaravelSpaFacade::getSpaUrlForPath('forgot_password')
            );
        });

        Fortify::resetPasswordView(function ($request) {
            return redirect(LaravelSpaFacade::getSpaUrlForPath('reset_password') . '?token=' . $request->route('token') . '&email=' . $request->email);
        });

        Fortify::verifyEmailView(function () {
            return redirect(
                LaravelSpaFacade::getSpaUrlForPath('verify_email')
            );
        });

        Fortify::confirmPasswordView(function () {
            return redirect(
                LaravelSpaFacade::getSpaUrlForPath('confirm_password')
            );
        });
    }
}
