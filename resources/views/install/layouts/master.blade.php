<!DOCTYPE html>
<html lang="{{ config('app.locale', 'en') }}">
<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex,nofollow"/>
	<meta name="googlebot" content="noindex">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ URL::asset('assets/ico/apple-touch-icon-144-precomposed.png') }}">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ URL::asset('assets/ico/apple-touch-icon-114-precomposed.png') }}">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ URL::asset('assets/ico/apple-touch-icon-72-precomposed.png') }}">
	<link rel="apple-touch-icon-precomposed" href="{{ URL::asset('assets/ico/apple-touch-icon-57-precomposed.png') }}">
	<link rel="shortcut icon" href="{{ URL::asset('assets/ico/favicon.png') }}">
	<title>@yield('title')</title>

	@yield('before_styles')

	<link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">

	@yield('after_styles')

    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<script>
		paceOptions = {
			elements: true
		};
	</script>
	<script src="{{ URL::asset('assets/js/pace.min.js') }}"></script>
</head>
<body>
<div id="wrapper">

	@section('header')
		@include('install.layouts.inc.header')
	@show

	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<h1 class="text-center title-1" style="text-transform: none; margin: 50px 0 20px; font-weight: bold;">{{ trans('messages.installation') }}</h1>

				@include('install._steps')

				@if (count($errors) > 0)
					<div class="alert alert-danger alert-noborder" style="margin-top: 25px;">
						@foreach ($errors->all() as $key => $error)
							<p class="text-semibold">{!! $error !!}</p>
						@endforeach
					</div>
				@endif
			</div>
		</div>
	</div>

	<div class="main-container">
		<div class="container">
			<div class="section-content">
				<div class="col-md-12 inner-box">
					@yield('content')
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				@section('footer')
					@include('install.layouts.inc.footer')
				@show
			</div>
		</div>
	</div>

</div>

@yield('before_scripts')

<script>
	/* Init. vars */
	var siteUrl = '{{ url('/') }}';
	var languageCode = '{{ config('app.locale') }}';
	var countryCode = '{{ config('country.code', 0) }}';

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
		$(".selecter").select2({
			language: '{{ config('app.locale', 'en') }}',
			dropdownAutoWidth: 'true',
			/*minimumResultsForSearch: Infinity*/
		});
	});
</script>

@yield('after_scripts')

</body>
</html>
