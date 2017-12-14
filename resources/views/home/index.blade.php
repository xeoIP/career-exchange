@extends('layouts.master')

@section('banner')
	@parent
	@include('home.inc.banner')
@endsection

@section('content')
	<div class="main-container" id="homepage">
		<div class="container">

			<div class="row">
				@if (Session::has('message'))
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						{{ session('message') }}
					</div>
				@endif

				@if (Session::has('flash_notification'))
					<div class="container" style="margin-bottom: -10px; margin-top: -10px;">
						<div class="row">
							<div class="col-lg-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif
			</div>

			@if (isset($sections) and $sections->count() > 0)
				@foreach($sections as $section)
					@if (view()->exists($section->view))
						@include($section->view)
					@endif
				@endforeach
			@endif

		</div>
	</div>
@endsection

@section('after_scripts')
@endsection
