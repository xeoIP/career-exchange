<?php

namespace Larapen\LaravelMetaTags;

use Torann\LaravelMetaTags\MetaTagsServiceProvider as TorannMetaTagsServiceProvider;

class MetaTagsServiceProvider extends TorannMetaTagsServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('metatag', function ($app) {
            return new MetaTag($app['request'], $app['config']['meta-tags'], $app['config']->get('app.locale'));
        });
    }
}
