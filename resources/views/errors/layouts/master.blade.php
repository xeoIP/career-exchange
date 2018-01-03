<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex,nofollow"/>
	<meta name="googlebot" content="noindex">
	<link rel="shortcut icon" href="{{ \Storage::url(config('settings.app_favicon')) . getPictureVersion() }}">
	<title>@yield('title')</title>

	@yield('before_styles')

	<link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
	<link href="{{ url('css/custom.css') . getPictureVersion() }}" rel="stylesheet">

	@yield('after_styles')

	@if (config('settings.custom_css'))
		<style type="text/css">
            <?php
            $customCss = config('settings.custom_css');
            $customCss = preg_replace('/<[^>]+>/i', '', $customCss);

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
<body class="{{ config('settings.app_skin') }}">

<div id="wrapper">

	@section('header')
		@if (Auth::check() and isset($user))
			@include('errors.layouts.inc.header', ['user' => $user])
		@else
			@include('errors.layouts.inc.header')
		@endif
	@show

	@yield('content')

	@section('info')
	@show

	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				@section('footer')
					@include('errors.layouts.inc.footer')
				@show
			</div>
		</div>
	</div>

</div>

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

@yield('after_scripts')

<script>
<?php
    $trackingCode = config('settings.tracking_code');
    $trackingCode = preg_replace('#<script(.*?)>(.*?)</script>#is', '$2', $trackingCode);
    echo $trackingCode . "\n";
?>
</script>
</body>
</html>
