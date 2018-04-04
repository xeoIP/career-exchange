<?php
$fullUrl = url(\Illuminate\Support\Facades\Request::getRequestUri());
?>
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($country) and $country->has('lang'))
        @if (config('app.locale') != $country->get('lang')->get('abbr'))
            <meta name="robots" content="noindex">
            <meta name="googlebot" content="noindex">
        @endif
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-title" content="{{ config('settings.app_name') }}">
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
          href="{{ \Storage::url('app/default/ico/apple-touch-icon-144-precomposed.png') . getPictureVersion() }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="{{ \Storage::url('app/default/ico/apple-touch-icon-114-precomposed.png') . getPictureVersion() }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
          href="{{ \Storage::url('app/default/ico/apple-touch-icon-72-precomposed.png') . getPictureVersion() }}">
    <link rel="apple-touch-icon-precomposed"
          href="{{ \Storage::url('app/default/ico/apple-touch-icon-57-precomposed.png') . getPictureVersion() }}">
    <link rel="shortcut icon" href="{{ \Storage::url(config('settings.app_favicon')) . getPictureVersion() }}">
    <title>{{ MetaTag::get('title') }}</title>
    {!! MetaTag::tag('description') !!}{!! MetaTag::tag('keywords') !!}
    <link rel="canonical" href="{{ $fullUrl }}"/>
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        @if (strtolower($localeCode) != strtolower(config('app.locale')))
            <link rel="alternate" href="{{ LaravelLocalization::getLocalizedURL($localeCode) }}"
                  hreflang="{{ strtolower($localeCode) }}"/>
        @endif
    @endforeach
    @if (count($dnsPrefetch) > 0)
        @foreach($dnsPrefetch as $dns)
            <link rel="dns-prefetch" href="{{ $dns }}">
        @endforeach
    @endif
    @if (isset($post))
        @if (isVerifiedPost($post))
            @if (config('services.facebook.client_id'))
                <meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}"/>
            @endif
            {!! $og->renderTags() !!}
            {!! MetaTag::twitterCard() !!}
        @endif
    @else
        @if (config('services.facebook.client_id'))
            <meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}"/>
        @endif
        {!! $og->renderTags() !!}
        {!! MetaTag::twitterCard() !!}
    @endif
    @if (config('settings.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('settings.google_site_verification') }}"/>
    @endif
    @if (config('settings.msvalidate'))
        <meta name="msvalidate.01" content="{{ config('settings.msvalidate') }}"/>
    @endif
    @if (config('settings.alexa_verify_id'))
        <meta name="alexaVerifyID" content="{{ config('settings.alexa_verify_id') }}"/>
    @endif

    @yield('before_styles')

    <link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
    <link href="{{ url('css/custom.css') . getPictureVersion() }}" rel="stylesheet">

    @yield('after_styles')

    @if (isset($installedPlugins) and count($installedPlugins) > 0)
        @foreach($installedPlugins as $pluginName)
            @yield($pluginName . '_styles')
        @endforeach
    @endif

    @if (config('settings.custom_css'))
        <style type="text/css">
            <?php
            $customCss = config( 'settings.custom_css' );
            $customCss = preg_replace( '/<[^>]+>/i', '', $customCss );

            echo $customCss . "\n";
            ?>
        </style>
        @endif

	<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script>
            paceOptions = {
                elements: true
            };
        </script>
        <script src="{{ url('assets/js/pace.min.js') }}"></script>
</head>
<body class="fullwidth {{ config('app.skin') }}">

<div id="wrapper">
    @section('header')
        @if (Auth::check())
            @include('layouts.inc.header', ['user' => $user])
        @else
            @include('layouts.inc.header')
        @endif
    @show

    @section('banner')
    @show

    @section('wizard')
    @show

    @if (isset($siteCountryInfo))
        <div class="container" style="margin-bottom: -30px; margin-top: 20px;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-warning">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! $siteCountryInfo !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    @yield('content')

    @section('info')
    @show

    <div id="footer">
        @section('footer')
            @include('layouts.inc.footer')
        @show
    </div>

</div>

@section('modal_location')
@show
@section('modal_abuse')
@show
@section('modal_message')
@show

@yield('before_scripts')

<script>
    /* Init. Translation vars */
    var langLayout = {
        'hideMaxListItems': {
            'moreText': "{{ t('View More') }}",
            'lessText': "{{ t('View Less') }}"
        }
    };
</script>

<script src="{{ url(mix('js/app.js')) }}"></script>
@if (file_exists(public_path() . '/assets/plugins/select2/js/i18n/'.config('app.locale').'.js'))
    <script src="{{ url('assets/plugins/select2/js/i18n/'.config('app.locale').'.js') }}"></script>
@endif

<script>
    $(document).ready(function () {
        /* Select Boxes */
        $('.selecter').select2({
            language: '{{ config('app.locale') }}',
            dropdownAutoWidth: 'true',
            minimumResultsForSearch: Infinity
        });
        /* Searchable Select Boxes */
        $('.sselecter').select2({
            language: '{{ config('app.locale') }}',
            dropdownAutoWidth: 'true'
        });

        /* Social Share */
        $('.share').ShareLink({
            title: '{{ addslashes(MetaTag::get('title')) }}',
            text: '{!! addslashes(MetaTag::get('title')) !!}',
            url: '{!! $fullUrl !!}',
            width: 640,
            height: 480
        });
    });
</script>

@yield('after_scripts')

@if (isset($installedPlugins) and count($installedPlugins) > 0)
    @foreach($installedPlugins as $pluginName)
        @yield($pluginName . '_scripts')
    @endforeach
@endif

<script>
    <?php
    $trackingCode = config( 'settings.tracking_code' );
    $trackingCode = preg_replace( '#<script(.*?)>(.*?)</script>#is', '$2', $trackingCode );
    echo $trackingCode . "\n";
    ?>
</script>
</body>
</html>
