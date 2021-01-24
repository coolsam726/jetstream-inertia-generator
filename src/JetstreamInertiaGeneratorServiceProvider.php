<?php

namespace Savannabits\JetstreamInertiaGenerator;

use Illuminate\Support\ServiceProvider;

class JetstreamInertiaGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->commands([
            JetstreamInertiaGenerator::class,
            Generators\Model::class,
            Generators\ApiController::class,
            Generators\Controller::class,
            Generators\ViewIndex::class,
            Generators\ViewForm::class,
            Generators\ViewFullForm::class,
            Generators\ModelFactory::class,
            Generators\Routes::class,
            Generators\ApiRoutes::class,
            Generators\IndexRequest::class,
            Generators\StoreRequest::class,
            Generators\UpdateRequest::class,
            Generators\DestroyRequest::class,
            Generators\ImpersonalLoginRequest::class,
            Generators\BulkDestroyRequest::class,
            Generators\Lang::class,
            Generators\Permissions::class,
            Generators\Export::class,
        ]);
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'jetstream-inertia-generator');
         $this->loadViewsFrom(__DIR__.'/../resources/views', 'jig');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('jig.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/jetstream-inertia-generator'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/jetstream-inertia-generator'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/jetstream-inertia-generator'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'jig');

        // Register the main class to use with the facade
        $this->app->singleton('jetstream-inertia-generator', function () {
            return new JetstreamInertiaGenerator;
        });
    }
}