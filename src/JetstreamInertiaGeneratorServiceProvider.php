<?php

namespace Savannabits\JetstreamInertiaGenerator;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Savannabits\JetstreamInertiaGenerator\Helpers\JigInstaller;
use Savannabits\JetstreamInertiaGenerator\Middleware\JigMiddleware;

class JetstreamInertiaGeneratorServiceProvider extends RouteServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        parent::boot();
        \DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        $this->commands([
            JetstreamInertiaGenerator::class,
            RoleGenerator::class,
            PermissionsGenerator::class,
            UsersGenerator::class,
            Generators\Model::class,
            Generators\Policy::class,
            Generators\Repository::class,
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
//            Generators\ImpersonalLoginRequest::class,
//            Generators\BulkDestroyRequest::class,
//            Generators\Lang::class,
            Generators\Permissions::class,
            Generators\Export::class,
        ]);
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'jetstream-inertia-generator');
         $this->loadViewsFrom(__DIR__.'/../resources/views', 'jig');
//         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        /**
         * @var Router $router
         */
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('jig',JigMiddleware::class);
         if (file_exists(base_path('routes/jig.php'))) {

             $this->routes(function() {
                 Route::middleware(['web','jig'])
                     ->namespace($this->namespace)
                     ->group(base_path('routes/jig.php'));
             });
         } else {
             $this->loadRoutesFrom(__DIR__.'/routes.php');
         }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('jig.php'),
                __DIR__.'/../config/vite.php' => config_path('vite.php'),
            ], 'jig-config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/published-views' => resource_path('views'),
            ], 'jig-blade-templates');

            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js'),
            ], 'jig-views');

            $this->publishes([
                __DIR__.'/../resources/scripts' => resource_path('scripts'),
            ], 'jig-scripts');

            $this->publishes([
                __DIR__.'/../resources/css' => resource_path('css'),
            ], 'jig-css');

            $this->publishes([
                /* __DIR__.'/../database/migrations/seed_admin_role_and_user.php' => database_path("migrations/".now()->format("Y_m_d_H_i_s"). "_seed_admin_role_and_user.php"), */
            ], 'jig-migrations');

            $this->publishes([
                __DIR__.'/../database/Seeders' => database_path("seeders"),
            ], 'jig-seeders');

            $this->publishes([
                __DIR__.'/../resources/compiler-configs' => base_path(''),
            ], 'jig-compiler-configs');

            $this->publishes([
                __DIR__.'/routes.php' => base_path('routes/jig.php'),
            ], 'jig-routes');


            // Publishing assets.
            $this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/jig'),
            ], 'jig-assets');

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/jetstream-inertia-generator'),
            ], 'lang');*/

            // Registering package commands.
             $this->commands([JigInstaller::class]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        parent::register();
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'jig');
        // Register the main class to use with the facade
        $this->app->singleton('jetstream-inertia-generator', function () {
            return new JetstreamInertiaGenerator;
        });
    }
}
