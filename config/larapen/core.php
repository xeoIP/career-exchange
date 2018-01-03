<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Default Logo
     |--------------------------------------------------------------------------
     |
     */

    'logo' => 'app/default/logo.png',

    /*
     |--------------------------------------------------------------------------
     | Default Favicon
     |--------------------------------------------------------------------------
     |
     */

    'favicon' => 'app/default/ico/favicon.png',

    /*
     |--------------------------------------------------------------------------
     | Default ads picture & Default ads pictures sizes
     |--------------------------------------------------------------------------
     |
     */

    'picture' => [
        'default' => 'app/default/picture.jpg',
        'size' => [
            'width'  => 1000,
            'height' => 1000,
        ],
        'quality' => 100,
        'resize' => [
            'logo'   => '500x100',
            'square' => '400x400', // ex: Categories
            'small'  => '120x90',
            'medium' => '320x240',
            'big'    => '816x460',
            'large'  => '1000x1000'
        ],
        'versioned' => env('PICTURE_VERSIONED', false),
        'version'   => env('PICTURE_VERSION', 1),
    ],

    /*
     |--------------------------------------------------------------------------
     | Default user profile picture
     |--------------------------------------------------------------------------
     |
     */

    'photo' => '',

    /*
     |--------------------------------------------------------------------------
     | Countries SVG maps folder & URL base
     |--------------------------------------------------------------------------
     |
     */

    'maps' => [
        'path'    => public_path('images/maps') . '/',
        'urlBase' => 'images/maps/',
    ],

    /*
     |--------------------------------------------------------------------------
     | Set as default language the browser language
     |--------------------------------------------------------------------------
     |
     */

    'detect_browser_language' => false,

    /*
     |--------------------------------------------------------------------------
     | Optimize your links for SEO (for International website)
     |--------------------------------------------------------------------------
     |
     */

    'multi_countries_website' => env('MULTI_COUNTRIES_SEO_LINKS', false),

	/*
     |--------------------------------------------------------------------------
     | Force links to use the HTTPS protocol
     |--------------------------------------------------------------------------
     |
     */

	  'force_https' => env('FORCE_HTTPS', false),

    /*
     |--------------------------------------------------------------------------
     | Plugins Path & Namespace
     |--------------------------------------------------------------------------
     |
     */

    'plugin' => [
        'path'      => app_path('Plugins') . '/',
        'namespace' => '\\App\Plugins\\',
    ],

    /*
     |--------------------------------------------------------------------------
     | Managing User's Fields (Phone, Email & Username)
     |--------------------------------------------------------------------------
     |
     | When 'disable.phone' and 'disable.email' are TRUE,
     | the script use the email field by default.
     |
     */

    'disable' => [
        'phone'    => env('DISABLE_PHONE', true),
        'email'    => env('DISABLE_EMAIL', false),
        'username' => env('DISABLE_USERNAME', true),
    ],

    /*
     |--------------------------------------------------------------------------
     | Disallowing usernames that match reserved names
     |--------------------------------------------------------------------------
     |
     */

    'reserved_usernames' => [
        'admin',
        'api',
        'profile',
        //...
    ],

    /*
     |--------------------------------------------------------------------------
     | Custom Prefix for the new locations (Administratives Divisions) Codes
     |--------------------------------------------------------------------------
     |
     */

    'location_code_prefix' => 'Z',

    /*
     |--------------------------------------------------------------------------
     | Mile use countries (By default, the script use Kilometer)
     |--------------------------------------------------------------------------
     |
     */

    'mile_use_countries' => ['US','UK'],

];
