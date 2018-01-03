<?php

namespace Larapen\Admin;

use Illuminate\Support\Facades\Route;

class RouteCrud
{
    public static function resource($name, $controller, array $options = [])
    {
        // CRUD Routes
        Route::post($name . '/search', ['as' => 'crud.' . $name . '.search', 'uses' => $controller . '@search',]);
        Route::get($name . '/reorder', ['as' => 'crud.' . $name . '.reorder', 'uses' => $controller . '@reorder',]);
        Route::get($name . '/reorder/{lang}', ['as' => 'crud.' . $name . '.reorder', 'uses' => $controller . '@reorder',]);
        Route::post($name . '/reorder', ['as' => 'crud.' . $name . '.save.reorder', 'uses' => $controller . '@saveReorder',]);
        Route::post($name . '/reorder/{lang}', ['as' => 'crud.' . $name . '.save.reorder', 'uses' => $controller . '@saveReorder',]);
        Route::get($name . '/{id}/details', ['as' => 'crud.' . $name . '.showDetailsRow', 'uses' => $controller . '@showDetailsRow',]);
        Route::get($name . '/{id}/translate/{lang}', ['as' => 'crud.' . $name . '.translateItem', 'uses' => $controller . '@translateItem',]);

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