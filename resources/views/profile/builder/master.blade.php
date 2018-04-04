<?php
$fullUrl = url(\Illuminate\Support\Facades\Request::getRequestUri());
?>
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

    <link rel="shortcut icon" href="{{ \Storage::url(config('settings.app_favicon')) . getPictureVersion() }}">
    <title>{{ MetaTag::get('title') }}</title>
    {!! MetaTag::tag('description') !!}{!! MetaTag::tag('keywords') !!}
    <link rel="canonical" href="{{ $fullUrl }}"/>

    @yield('before_styles')

    <link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">

    @yield('after_styles')

    @yield('before_scripts')

    <script src="{{ url(mix('js/app_bootstrap.js')) }}"></script>
    <script src="{{ url(mix('js/profile_builder.js')) }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    @yield('after_scripts')
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

    <div id="profile_builder">
        @yield('content')
    </div>

    <div id="footer">
        @section('footer')
            @include('layouts.inc.footer')
        @show
    </div>
</div>

</body>
</html>


