@extends('layouts.master')

@section('wizard')
	@include('post.inc.wizard')
@endsection

@section('content')
	<div class="main-container">
		<div class="container">
			<div class="row">

				@if (Session::has('flash_notification'))
					<div class="container" style="margin-bottom: -10px; margin-top: -10px;">
						<div class="row">
							<div class="col-lg-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-md-12 page-content">

					@if (Session::has('success'))
						<div class="inner-box category-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="alert alert-success pgray  alert-lg" role="alert">
										<h2 class="no-margin no-padding">&#10004; {{ t('Congratulations!') }}</h2>
										<p>{{ session('message') }} <a href="{{ lurl('/') }}">{{ t('Homepage') }}</a></p>
									</div>
								</div>
							</div>
						</div>
					@endif

				</div>
			</div>
		</div>
	</div>
@endsection
