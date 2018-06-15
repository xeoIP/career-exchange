@extends('errors.layouts.master')

@section('search')
	@parent
	@include('errors.layouts.inc.search')
@endsection

@section('content')
	<div class="main-container inner-page">
		<div class="container">
			<div class="section-content">
				<div class="row">

					<div class="col-md-12 page-content">

						<div class="error-page" style="margin: 100px 0;">
							<h2 class="headline text-center" style="font-size: 180px; float: none;"> 500</h2>
							<div class="text-center m-l-0" style="margin-top: 60px;">
								<h3 class="m-t-0"><i class="fa fa-warning"></i> 500 Internal Server Error.</h3>
								<p>
									<?php
									$default_error_message = "An internal server error has occurred. If the error persists please contact the development team.";
									?>
									{!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}
								</p>
							</div>
						</div>

					</div>

				</div>
			</div>

            <?php
				$requirements = [];
				if (!version_compare(PHP_VERSION, '5.6.4', '>=')) {
					$requirements[] = 'PHP 5.6.4 or higher is required.';
				}
				if (!extension_loaded('openssl')) {
					$requirements[] = 'OpenSSL PHP Extension is required.';
				}
				if (!extension_loaded('mbstring')) {
					$requirements[] = 'Mbstring PHP Extension is required.';
				}
				if (!extension_loaded('pdo')) {
					$requirements[] = 'PDO PHP Extension is required.';
				}
				if (!extension_loaded('tokenizer')) {
					$requirements[] = 'Tokenizer PHP Extension is required.';
				}
				if (!extension_loaded('xml')) {
					$requirements[] = 'XML PHP Extension is required.';
				}
				if (!extension_loaded('fileinfo')) {
					$requirements[] = 'PHP Fileinfo Extension is required.';
				}
				if (!(extension_loaded('gd') && function_exists('gd_info'))) {
					$requirements[] = 'PHP GD Library is required.';
				}
            ?>
			@if (isset($requirements))
			<div class="row">
				<div class="col-md-12">
					<ul class="installation">
						@foreach ($requirements as $key => $item)
							<li>
								<i class="icon-cancel text-danger"></i>
								<h5 class="title-5">
									Error #{{ $key }}
								</h5>
								<p>
									{{ $item }}
								</p>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
			@endif

		</div>
	</div>
	<!-- /.main-container -->
@endsection
