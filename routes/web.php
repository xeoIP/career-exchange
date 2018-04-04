<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Front-end
|--------------------------------------------------------------------------
|
| The not translated front-end routes
|
*/
Route::group([
    'middleware' => ['web'],
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    // AJAX
    Route::group(['prefix' => 'ajax'], function ($router) {
        Route::post('category/sub-categories', 'Ajax\CategoryController@getSubCategories');
        Route::post('save/post', 'Ajax\PostController@savePost');
        Route::post('save/search', 'Ajax\PostController@saveSearch');
        Route::post('post/phone', 'Ajax\PostController@getPhone');
    });

    // SEO
    Route::get('robots.txt', 'RobotsController@index');
    Route::get('sitemaps.xml', 'SitemapsController@index');

});


/*
|--------------------------------------------------------------------------
| Front-end
|--------------------------------------------------------------------------
|
| The translated front-end routes
|
*/
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['local'],
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    Route::group(['middleware' => ['web']], function ($router) {
        // HOMEPAGE
        Route::group(['middleware' => 'httpCache:yes'], function ($router) {
            Route::get('/', 'HomeController@index');
            Route::get(LaravelLocalization::transRoute('routes.countries'), 'CountriesController@index');
        });

        // AUTH
        Route::group(['middleware' => ['guest', 'preventBackHistory']], function () {
            // Registration Routes...
            Route::get(LaravelLocalization::transRoute('routes.register'), 'Auth\RegisterController@showRegistrationForm');
            Route::post(LaravelLocalization::transRoute('routes.register'), 'Auth\RegisterController@register');
            Route::get('register/finish', 'Auth\RegisterController@finish');

            // Authentication Routes...
            Route::get(LaravelLocalization::transRoute('routes.login'), 'Auth\LoginController@showLoginForm');
            Route::post(LaravelLocalization::transRoute('routes.login'), 'Auth\LoginController@login');

            // Forgot Password Routes...
            Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
            Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');

            // Reset Password using Token
            Route::get('password/token', 'Auth\ForgotPasswordController@showTokenRequestForm');
            Route::post('password/token', 'Auth\ForgotPasswordController@sendResetToken');

            // Reset Password using Link (Core Routes...)
            Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
            Route::post('password/reset', 'Auth\ResetPasswordController@reset');

            // Social Authentication
            Route::get('auth/facebook', 'Auth\SocialController@redirectToProvider');
            Route::get('auth/facebook/callback', 'Auth\SocialController@handleProviderCallback');
            Route::get('auth/google', 'Auth\SocialController@redirectToProvider');
            Route::get('auth/google/callback', 'Auth\SocialController@handleProviderCallback');
            Route::get('auth/twitter', 'Auth\SocialController@redirectToProvider');
            Route::get('auth/twitter/callback', 'Auth\SocialController@handleProviderCallback');
        });

        // Email Address or Phone Number verification
        $router->pattern('field', 'email|phone');
        Route::get('verify/user/{id}/resend/email', 'Auth\RegisterController@reSendVerificationEmail');
        Route::get('verify/user/{id}/resend/sms', 'Auth\RegisterController@reSendVerificationSms');
        Route::get('verify/user/{field}/{token?}', 'Auth\RegisterController@verification');
        Route::post('verify/user/{field}/{token?}', 'Auth\RegisterController@verification');

        // User Logout
        Route::get(LaravelLocalization::transRoute('routes.logout'), 'Auth\LoginController@logout');

        // POSTS
        Route::group(['namespace' => 'Post'], function ($router) {
            $router->pattern('id', '[0-9]+');
            Route::get('posts/create/{tmpToken?}', 'CreateController@getForm');
            Route::post('posts/create', 'CreateController@postForm');
            Route::put('posts/create/{tmpToken}', 'CreateController@postForm');
            Route::get('posts/create/{tmpToken}/packages', 'PackageController@getForm');
            Route::post('posts/create/{tmpToken}/packages', 'PackageController@postForm');
            Route::get('posts/create/{tmpToken}/finish', 'CreateController@finish');

            // Payment Gateway Success & Cancel
            Route::get('posts/create/{tmpToken}/payment/success', 'PackageController@paymentConfirmation');
            Route::get('posts/create/{tmpToken}/payment/cancel', 'PackageController@paymentCancel');

            // Email Address or Phone Number verification
            $router->pattern('field', 'email|phone');
            Route::get('verify/post/{id}/resend/email', 'CreateController@reSendVerificationEmail');
            Route::get('verify/post/{id}/resend/sms', 'CreateController@reSendVerificationSms');
            Route::get('verify/post/{field}/{token?}', 'CreateController@verification');
            Route::post('verify/post/{field}/{token?}', 'CreateController@verification');

            Route::group(['middleware' => 'auth'], function ($router) {
                $router->pattern('id', '[0-9]+');
                Route::get('posts/{id}/edit', 'EditController@getForm');
                Route::put('posts/{id}/edit', 'EditController@postForm');
                Route::get('posts/{id}/packages', 'PackageController@getForm');
                Route::post('posts/{id}/packages', 'PackageController@postForm');

                // Payment Gateway Success & Cancel
                Route::get('posts/{id}/payment/success', 'PackageController@paymentConfirmation');
                Route::get('posts/{id}/payment/cancel', 'PackageController@paymentCancel');
            });
            Route::get('{title}/{id}.html', 'DetailsController@index');
            Route::post('posts/{id}/contact', 'DetailsController@sendMessage');

            // Send report abuse
            Route::get('posts/{id}/report', 'ReportController@showReportForm');
            Route::post('posts/{id}/report', 'ReportController@sendReport');
        });
        Route::post('send-by-email', 'Search\SearchController@sendByEmail');


        // ACCOUNT
        Route::group(['middleware' => ['auth', 'bannedUser', 'preventBackHistory'], 'namespace' => 'Account'], function ($router) {
            $router->pattern('id', '[0-9]+');

            Route::get('account', 'EditController@index')->name('account.index');
            Route::put('account', 'EditController@updateDetails');
            Route::put('account/settings', 'EditController@updateSettings');
            Route::put('account/preferences', 'EditController@updatePreferences');
            Route::put('account/resume', 'ResumeController@resume');
            Route::get('account/resume', 'ResumeController@index');//->name('account.resume.view');//////////////////////
            Route::get('account/saved-search', 'PostsController@getSavedSearch');

            $router->pattern('pagePath', '(my-posts|archived|favorite|pending-approval|saved-search)+');
            Route::get('account/{pagePath}', 'PostsController@getPage');
            // Re-post archived Posts
            Route::get('account/{pagePath}/{id}/repost', 'PostsController@getArchivedPosts');

            // Delete Entry
            Route::get('account/{pagePath}/{id}/delete', 'PostsController@delete');
            Route::post('account/{pagePath}/delete', 'PostsController@delete');

            // Messages
            Route::get('account/messages', 'MessagesController@index');
            Route::post('account/messages/{id}/reply', 'MessagesController@reply');
            Route::get('account/messages/{id}/delete', 'MessagesController@delete');
            Route::post('account/messages/delete', 'MessagesController@delete');

            // Transactions
            Route::get('account/transactions', 'TransactionsController@index');

            // Close User's account
            Route::get('account/close', 'CloseController@index');
            Route::post('account/close', 'CloseController@submit');
        });


        // Country Code Pattern
        $countries = App\Helpers\Localization\Helpers\Country::transAll(App\Helpers\Localization\Country::getCountries());
        $countryCodePattern = implode('|', array_map('strtolower', array_keys($countries->all())));
        $router->pattern('countryCode', $countryCodePattern);


        // XML SITEMAPS
        Route::get('{countryCode}/sitemaps.xml', 'SitemapsController@site');
        Route::get('{countryCode}/sitemaps/pages.xml', 'SitemapsController@pages');
        Route::get('{countryCode}/sitemaps/categories.xml', 'SitemapsController@categories');
        Route::get('{countryCode}/sitemaps/cities.xml', 'SitemapsController@cities');
        Route::get('{countryCode}/sitemaps/posts.xml', 'SitemapsController@posts');


        // STATICS PAGES
        Route::group(['middleware' => 'httpCache:yes'], function ($router) {
            Route::get(LaravelLocalization::transRoute('routes.page'), 'PageController@index');
            Route::get(LaravelLocalization::transRoute('routes.contact'), 'PageController@contact');
            Route::post(LaravelLocalization::transRoute('routes.contact'), 'PageController@contactPost');
            Route::get(LaravelLocalization::transRoute('routes.sitemap'), 'SitemapController@index');
        });


        // DYNAMIC URL PAGES
        $router->pattern('id', '[0-9]+');
        $router->pattern('username', '[a-zA-Z0-9]+');
        Route::get(LaravelLocalization::transRoute('routes.search'), 'Search\SearchController@index');
        Route::get(LaravelLocalization::transRoute('routes.search-user'), 'Search\UserController@index');
        Route::get(LaravelLocalization::transRoute('routes.search-username'), 'Search\UserController@profile');
        Route::get(LaravelLocalization::transRoute('routes.search-company'), 'Search\CompanyController@index');
        Route::get(LaravelLocalization::transRoute('routes.search-city'), 'Search\CityController@index');
        Route::get(LaravelLocalization::transRoute('routes.search-subCat'), 'Search\CategoryController@index');
        Route::get(LaravelLocalization::transRoute('routes.search-cat'), 'Search\CategoryController@index');

        //Profile builder
        Route::group(['middleware' => ['auth', 'bannedUser']], function () {
            //step 1
            Route::get('/profile/builder/1', 'ProfileBuilderController@stepOne')->name('profile.builder.step_one');
            Route::post('/profile/builder/1', 'ProfileBuilderController@stepOnePost')->name('profile.builder.step_one_post');

            //step 2
            Route::get('/profile/builder/2', 'ProfileBuilderController@stepTwo')->name('profile.builder.step_two');
            Route::post('/profile/builder/2', 'ProfileBuilderController@stepTwoPost')->name('profile.builder.step_two_post');

            //step 3
            Route::get('/profile/builder/3', 'ProfileBuilderController@stepThree')->name('profile.builder.step_three');
            Route::post('/profile/builder/3', 'ProfileBuilderController@stepThreePost')->name('profile.builder.step_three_post');

            //step 4
            Route::get('/profile/builder/4', 'ProfileBuilderController@stepFour')->name('profile.builder.step_four');
            Route::post('/profile/builder/4', 'ProfileBuilderController@stepFourPost')->name('profile.builder.step_four_post');

            //step 5
            Route::get('/profile/builder/5', 'ProfileBuilderController@stepFive')->name('profile.builder.step_five');
           // Route::post('/profile/builder/5', 'ProfileBuilderController@stepFivePost')->name('profile.builder.step_five_post');
            Route::post('/profile/builder/5', 'ProfileBuilderController@store')->name('profile.builder.store');

            Route::get('/profile/builder/view', 'ProfileBuilderController@show')->name('account.resume.view');
        });
    });
});

//


/*
|--------------------------------------------------------------------------
| Back-end
|--------------------------------------------------------------------------
|
| The admin panel routes
|
*/
Route::group([
    'middleware' => ['admin', 'bannedUser', 'installChecker', 'preventBackHistory'],
    'prefix' => config('larapen.admin.route_prefix', 'admin'),
    'namespace' => 'App\Http\Controllers\Admin',
], function () {
    // CRUD
    CRUD::resource('advertising', 'AdvertisingController');
    CRUD::resource('blacklist', 'BlacklistController');
    CRUD::resource('category', 'CategoryController');
    CRUD::resource('category/{catId}/sub_category', 'SubCategoryController');
    CRUD::resource('city', 'CityController');
    CRUD::resource('country', 'CountryController');
    CRUD::resource('country/{countryCode}/city', 'CityController');
    CRUD::resource('country/{countryCode}/loc_admin1', 'SubAdmin1Controller');
    CRUD::resource('currency', 'CurrencyController');
    CRUD::resource('gender', 'GenderController');
    CRUD::resource('home_section', 'HomeSectionController');
    CRUD::resource('loc_admin1/{admin1Code}/city', 'CityController');
    CRUD::resource('loc_admin1/{admin1Code}/loc_admin2', 'SubAdmin2Controller');
    CRUD::resource('loc_admin2/{admin2Code}/city', 'CityController');
    CRUD::resource('meta_tag', 'MetaTagController');
    CRUD::resource('package', 'PackageController');
    CRUD::resource('page', 'PageController');
    CRUD::resource('payment', 'PaymentController');
    CRUD::resource('payment_method', 'PaymentMethodController');
    CRUD::resource('picture', 'PictureController');
    CRUD::resource('post', 'PostController');
    CRUD::resource('p_type', 'PostTypeController');
    CRUD::resource('report_type', 'ReportTypeController');
    CRUD::resource('salary_type', 'SalaryTypeController');
    CRUD::resource('time_zone', 'TimeZoneController');
    CRUD::resource('user', 'UserController');

    // Others
    Route::get('account', 'UserController@account');
    Route::post('ajax/{table}/{field}', 'AjaxController@saveAjaxRequest');
    Route::get('clear_cache', 'CacheController@clear');

    // Re-send Email or Phone verification message
    Route::get('verify/user/{id}/resend/email', 'UserController@reSendVerificationEmail');
    Route::get('verify/user/{id}/resend/sms', 'UserController@reSendVerificationSms');
    Route::get('verify/post/{id}/resend/email', 'PostController@reSendVerificationEmail');
    Route::get('verify/post/{id}/resend/sms', 'PostController@reSendVerificationSms');

    // Plugins
    Route::get('plugin', 'PluginController@index');
    Route::get('plugin/{plugin}/install', 'PluginController@install');
    Route::get('plugin/{plugin}/uninstall', 'PluginController@uninstall');
    Route::get('plugin/{plugin}/delete', 'PluginController@delete');
});
