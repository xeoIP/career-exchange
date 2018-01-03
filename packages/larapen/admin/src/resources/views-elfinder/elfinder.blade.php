@extends('admin::layout')

@section('after_styles')

    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="<?= asset($dir.'/css/elfinder.min.css') ?>">

@endsection

@section('header')
    <section class="content-header">
      <h1>
        File manager
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/dashboard') }}">Admin</a></li>
        <li class="active">File Manager</li>
      </ol>
    </section>
@endsection

@section('content')

    <!-- Element where elFinder will be created (REQUIRED) -->
    <div id="elfinder"></div>

@endsection

@section('after_scripts')
	<!-- jQuery and jQuery UI (REQUIRED) -->
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
	<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

	<link rel="stylesheet" type="text/css" href="<?= asset($dir.'/css/theme.css') ?>">

	<!-- elFinder JS (REQUIRED) -->
	<script src="<?= asset($dir.'/js/elfinder.min.js') ?>"></script>

	<?php if($locale){ ?>
	<!-- elFinder translation (OPTIONAL) -->
	<script src="<?= asset($dir."/js/i18n/elfinder.$locale.js") ?>"></script>
	<?php } ?>

	<!-- elFinder initialization (REQUIRED) -->
	<script type="text/javascript" charset="utf-8">
		// Documentation for client options:
		// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
		$().ready(function() {
			$('#elfinder').elfinder({
				// set your elFinder options here
				<?php if($locale){ ?>
				lang: '<?= $locale ?>', // locale
				<?php } ?>
				customData: {
					_token: '<?= csrf_token() ?>'
				},
				soundPath : '/packages/barryvdh/elfinder/sounds/',
				url : '<?= route("elfinder.connector") ?>'  // connector URL
			});
		});
	</script>
@endsection
