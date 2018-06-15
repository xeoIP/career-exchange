<?php

namespace Larapen\Elfinder;

use Barryvdh\Elfinder\Console;
use Illuminate\Routing\Router;

class ElfinderServiceProvider extends \Barryvdh\Elfinder\ElfinderServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        // Parent method
        $viewPath = __DIR__ . '/../resources/views';
        $this->loadViewsFrom($viewPath, 'elfinder');
        $this->publishes([$viewPath => base_path('resources/views/vendor/elfinder')], 'views');

        if (!defined('ELFINDER_IMG_PARENT_URL')) {
            define('ELFINDER_IMG_PARENT_URL', $this->app['url']->asset('packages/barryvdh/elfinder'));
        }

        // Set config
        $config = $this->app['config']->get('elfinder.route', []);
        config(['elfinder.root.prefix' => config('larapen.admin.route_prefix', 'admin') . '/elfinder']);
        config(['elfinder.root.middleware' => $config['middleware']]);


        // Set routes
        $router->group([
            'prefix'     => config('elfinder.root.prefix'),
            'middleware' => config('elfinder.root.middleware'),
            'namespace'  => 'Barryvdh\Elfinder',
        ], function ($router) {
            $router->get('/', 'ElfinderController@showIndex');
            $router->any('connector', ['as' => 'elfinder.connector', 'uses' => 'ElfinderController@showConnector']);
            $router->get('popup/{input_id}', ['as' => 'elfinder.popup', 'uses' => 'ElfinderController@showPopup']);
            $router->get('filepicker/{input_id}', ['as' => 'elfinder.filepicker', 'uses' => 'ElfinderController@showFilePicker']);
            $router->get('tinymce', ['as' => 'elfinder.tinymce', 'uses' => 'ElfinderController@showTinyMCE']);
            $router->get('tinymce4', ['as' => 'elfinder.tinymce4', 'uses' => 'ElfinderController@showTinyMCE4']);
            $router->get('ckeditor', ['as' => 'elfinder.ckeditor', 'uses' => 'ElfinderController@showCKeditor4']);
        });
    }
}
