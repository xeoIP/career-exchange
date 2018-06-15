<?php

namespace Larapen\Admin;

use App\Models\Language;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Route;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // LOAD THE VIEWS
        // First the published/overwritten views (in case they have any changes)
        $this->loadViewsFrom(resource_path('views/vendor/admin'), 'admin');
        // ... Then the stock views that come with the package, in case a published view might be missing
        $this->loadViewsFrom(realpath(__DIR__.'/resources/views'), 'admin');

        // LOAD THE LANGUAGES FILES
        $this->loadTranslationsFrom(realpath(__DIR__.'/resources/lang'), 'admin');

        // Use the vendor configuration file as fallback
        $this->mergeConfigFrom(__DIR__.'/config/admin.php', 'admin');
        $this->mergeConfigFrom(__DIR__.'/config/laravel-backup.php', 'admin');


        $this->registerAdminMiddleware($this->app->router);
        $this->setupRoutes($this->app->router);
        $this->publishFiles();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('admin', function ($app) {
            return new Admin($app);
        });

        // Register its dependencies
        $this->app->register(\Jenssegers\Date\DateServiceProvider::class);
        $this->app->register(\Prologue\Alerts\AlertsServiceProvider::class);
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\Intervention\Image\ImageServiceProvider::class);

        // Register their aliases
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Alert', \Prologue\Alerts\Facades\Alert::class);
        $loader->alias('Date', \Jenssegers\Date\Date::class);
        $loader->alias('CRUD', \Larapen\Admin\RouteCrud::class);
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('Html', \Collective\Html\HtmlFacade::class);
        $loader->alias('Image', \Intervention\Image\Facades\Image::class);
    }

    public function registerAdminMiddleware(Router $router)
    {
        // in Laravel 5.4
        if (method_exists($router, 'aliasMiddleware')) {
            Route::aliasMiddleware('admin', app\Http\Middleware\Admin::class);
            Route::aliasMiddleware('bannedUser', \App\Http\Middleware\BannedUser::class);
            Route::aliasMiddleware('installChecker', \App\Http\Middleware\InstallationChecker::class);
        }
        // in Laravel 5.3 and below
        else {
            Route::middleware('admin', app\Http\Middleware\Admin::class);
            Route::middleware('bannedUser', \App\Http\Middleware\BannedUser::class);
            Route::middleware('installChecker', \App\Http\Middleware\InstallationChecker::class);
        }
    }

    public function publishFiles()
    {
        // Publish lang files
        $this->publishes([__DIR__.'/resources/lang' => resource_path('lang/vendor/admin')], 'lang');

        // Publish views
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/admin')], 'views');

        // Publish error views
        $this->publishes([__DIR__.'/resources/error_views' => resource_path('views/errors')], 'errors');

        // Publish config file
        $this->publishes([__DIR__.'/config' => config_path()], 'config');

        // Publish public AdminLTE assets
        $this->publishes([base_path('vendor/almasaeed2010/adminlte') => public_path('vendor/adminlte')], 'adminlte');

        // Publish public Backpack CRUD assets
        $this->publishes([__DIR__.'/public' => public_path('vendor/admin')], 'public');

        // Publish custom files for elFinder
        $this->publishes([
            __DIR__.'/config/elfinder.php'      => config_path('elfinder.php'),
            __DIR__.'/resources/views-elfinder' => resource_path('views/vendor/elfinder'),
        ], 'elfinder');

        // Publish the migrations and seeds
        $this->publishes([__DIR__.'/database/migrations/' => database_path('migrations')], 'migrations');
        $this->publishes([__DIR__.'/database/seeds/' => database_path('seeds')], 'seeds');

        // Publish Backup config file
        $this->publishes([__DIR__.'/config/laravel-backup.php' => config_path('laravel-backup.php')], 'config');
    }

    /**
     * Define the routes for the application.
     *
     * @param Router $router
     */
    public function setupRoutes(Router $router)
    {
        // Admin Interface Routes
        $router->group(['namespace' => 'Larapen\Admin\app\Http\Controllers'], function ($router) {
            Route::group([
                'middleware' => ['web', 'installChecker'],
                'prefix'     => config('larapen.admin.route_prefix', 'admin'),
            ], function () {
                // Auth
                // if not otherwise configured, setup the auth routes
                if (config('larapen.admin.setup_auth_routes')) {
                    Route::auth();
                    Route::get('logout', 'Auth\LoginController@logout');
                }
                // Dashboard
                // if not otherwise configured, setup the dashboard routes
                if (config('larapen.admin.setup_dashboard_routes')) {
                    Route::group([
                        'middleware' => ['admin', 'bannedUser']
                    ], function () {
                        Route::get('dashboard', 'DashboardController@dashboard');
                        Route::get('/', 'DashboardController@redirect');
                    });
                }
            });
        });
        $router->group(['namespace' => 'Larapen\Admin\app\Http\Controllers'], function ($router) {
            Route::group([
                'middleware' => ['admin', 'bannedUser', 'installChecker'],
                'prefix'     => config('larapen.admin.route_prefix', 'admin'),
            ], function () {
                // Settings
                self::resource('setting', 'SettingController');

                // Language
                //Route::get('language/texts/{lang?}/{file?}', 'LanguageController@showTexts');
                //Route::post('language/texts/{lang}/{file}', 'LanguageController@updateTexts');
                self::resource('language', 'LanguageController');

                // Backup
                Route::get('backup', 'BackupController@index');
                Route::put('backup/create', 'BackupController@create');
                Route::get('backup/download/{file_name?}', 'BackupController@download');
                Route::delete('backup/delete/{file_name?}', 'BackupController@delete')->where('file_name', '(.*)');
            });
        });
    }

    /**
     * @param $name
     * @param $controller
     * @param array $options
     */
    public static function resource($name, $controller, array $options = [])
    {
        // CRUD Routes
        Route::post($name . '/search', ['as' => 'crud.' . $name . '.search', 'uses' => $controller . '@search']);
        Route::get($name . '/reorder', ['as' => 'crud.' . $name . '.reorder', 'uses' => $controller . '@reorder']);
        Route::get($name . '/reorder/{lang}', ['as' => 'crud.' . $name . '.reorder', 'uses' => $controller . '@reorder']);
        Route::post($name . '/reorder', ['as' => 'crud.' . $name . '.save.reorder', 'uses' => $controller . '@saveReorder']);
        Route::post($name . '/reorder/{lang}', ['as' => 'crud.' . $name . '.save.reorder', 'uses' => $controller . '@saveReorder']);
        Route::get($name . '/{id}/details', ['as' => 'crud.' . $name . '.showDetailsRow', 'uses' => $controller . '@showDetailsRow']);
        Route::get($name . '/{id}/translate/{lang}', ['as' => 'crud.' . $name . '.translateItem', 'uses' => $controller . '@translateItem']);

        $options_with_default_route_names = array_merge([
            'names' => [
                'index'   => 'crud.' . $name . '.index',
                'create'  => 'crud.' . $name . '.create',
                'store'   => 'crud.' . $name . '.store',
                'edit'    => 'crud.' . $name . '.edit',
                'update'  => 'crud.' . $name . '.update',
                'show'    => 'crud.' . $name . '.show',
                'destroy' => 'crud.' . $name . '.destroy',
            ],
        ], $options);

        Route::resource($name, $controller, $options_with_default_route_names);
    }
}
