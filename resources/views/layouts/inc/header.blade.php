<?php
// Search parameters
$queryString = ( Request::getQueryString() ? ( '?' . Request::getQueryString() ) : '' );
$pageRoute   = Request::route();
// Get the Default Language
$cacheExpiration = ( isset( $cacheExpiration ) ) ? $cacheExpiration : config( 'settings.app_cache_expiration', 60 );
$defaultLang     = Cache::remember( 'language.default', $cacheExpiration, function () {
    $defaultLang = \App\Models\Language::where( 'default', 1 )->first();

    return $defaultLang;
} );
if ( $pageRoute->uri == "/" ) {
    $pageRoute = true;
} else {
    $pageRoute = false;
}
?>

<!-- Header
================================================== -->
@if ($pageRoute)
    <header class="transparent sticky-header">
        @endif
        @if (!$pageRoute)
            <header class="sticky-header">
                @endif
                <div class="container">
                    <div class="sixteen columns">

                    @if (!$pageRoute)
                        <!-- Logo -->
                            <div id="logo">
                                <h1><a href="{{ lurl('/') }}">
                                        <img src="/images/logo-1.png"
                                             alt="{{ strtolower(config('settings.app_name')) }}" class="tooltipHere"
                                             title="" data-placement="bottom"
                                             data-toggle="tooltip" type="button"
                                             data-original-title="{{ config('settings.app_name') . ((isset($country) and $country->has('name')) ? ' ' . $country->get('name') : '') }}"/>
                                    </a></h1>
                            </div>
                    @endif

                    @if ($pageRoute)
                        <!-- Logo -->
                            <div id="logo">
                                <h1><a href="{{ lurl('/') }}">
                                        <img src="/images/logo-2.png"
                                             alt="{{ strtolower(config('settings.app_name')) }}" class="tooltipHere"
                                             title="" data-placement="bottom"
                                             data-toggle="tooltip" type="button"
                                             data-original-title="{{ config('settings.app_name') . ((isset($country) and $country->has('name')) ? ' ' . $country->get('name') : '') }}"/>
                                    </a></h1>
                            </div>
                    @endif

                    <!-- Menu -->
                        <nav id="navigation" class="menu">
                            <ul id="responsive">

                                <li><a href="{{ lurl('/') }}" id="current">Home</a></li>

                                <li><a href="{{ lurl('account') }}">Profile</a>
                                    <ul>
                                        <li><a href="#">Jobs Page</a></li>
                                        <li><a href="#">Resume Page</a></li>
                                    </ul>
                                </li>

                                <li><a href="#">For Candidates</a>
                                    <ul>
                                        <li><a href="#">Browse Jobs</a></li>
                                        <li><a href="#">Browse Categories</a></li>
                                        <li><a href="#">Add Resume</a></li>
                                        <li><a href="#">Manage Resumes</a></li>
                                        <li><a href="#">Job Alerts</a></li>
                                    </ul>
                                </li>

                                <li><a href="#">For Employers</a>
                                    <ul>
                                        <li><a href="#">Add Job</a></li>
                                        <li><a href="#">Manage Jobs</a></li>
                                        <li><a href="#">Manage Applications</a></li>
                                        <li><a href="#">Browse Resumes</a></li>
                                    </ul>
                                </li>
                            </ul>

                            @if (!auth()->user())
                                <ul class="responsive float-right">
                                    <li><a href="{{ lurl('register') }}"><i class="fa fa-user"></i> {{ t('Register') }}
                                        </a></li>
                                    <li><a href="{{ lurl('login') }}"><i class="fa fa-lock"></i>{{ t('Log In') }}</a>
                                    </li>
                                </ul>
                            @endif

                            @if (auth()->user())
                                <ul class="responsive float-right">
                                    <li><a href="{{ lurl('account') }}"><i class="fa fa-user"></i> {{ t('My Account') }}
                                        </a></li>
                                    <li><a href="{{ lurl('logout') }}"><i class="fa fa-lock"></i> Sign out</a></li>
                                </ul>
                            @endif
                        </nav>

                        <!-- Navigation -->
                        <div id="mobile-navigation">
                            <a href="#menu" class="menu-trigger"><i class="fa fa-reorder"></i> Menu</a>
                        </div>

                    </div>
                </div>
            </header>
            <div class="clearfix"></div>
    </header>
