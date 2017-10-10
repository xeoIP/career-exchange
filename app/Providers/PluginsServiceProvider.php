<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Route;

class PluginsServiceProvider extends ServiceProvider
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
        // Set routes
        $this->setupRoutes($this->app->router);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('plugins', function ($app) {
            return new Plugins($app);
        });

        // Load all Plugins Services Provider
        $pluginsList = \File::glob(config('larapen.core.plugin.path') . '*', GLOB_ONLYDIR);

        if (count($pluginsList) > 0) {
            foreach($pluginsList as $pluginPath) {
                // Get plugin folder name
                $pluginFolderName = strtolower(last(explode('/', $pluginPath)));

                // Get plugin details
                $plugin = load_plugin($pluginFolderName);

                if (!empty($plugin)) {
                    // Register the plugin
                    $this->app->register($plugin->provider);
                }
            }
        }
    }

    /**
     * Define the global routes for the plugins.
     *
     * @param Router $router
     */
    public function setupRoutes(Router $router)
    {
        // Public - Images
        Route::get('images/{plugin}/{filename}', function ($plugin, $filename)
        {
            $path = plugin_path($plugin, 'public/images/' . $filename);
            if (\File::exists($path)) {
                $file = \File::get($path);
                $type = \File::mimeType($path);

                $response = \Response::make($file, 200);
                $response->header("Content-Type", $type);

                return $response;
            }

            abort(404);
        });

        // Public - Assets
        Route::get('assets/{plugin}/{type}/{file}', function ($plugin, $type, $file)
        {
            $path = plugin_path($plugin, 'public/assets/' . $type . '/' . $file);
            if (\File::exists($path)) {
                //return response()->download($path, "$file");
                if ($type == 'js') {
                    return response()->file($path, array('Content-Type' => 'application/javascript'));
                } else {
                    return response()->file($path, array('Content-Type' => 'text/css'));
                }
            }

            abort(404);
        });
    }
}
