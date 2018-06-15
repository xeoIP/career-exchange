@extends('layouts.master')

@section('content')
	<div class="main-container">
		<div class="container">
			<div class="row">

				@if (isset($errors) and count($errors) > 0)
					<div class="col-lg-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif

				@if (session('code'))
					<div class="col-lg-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('code') }}</p>
						</div>
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

				<div class="col-lg-12">
					<div class="alert alert-info">
						{{ getTokenMessage() }}:
					</div>
				</div>

				<div class="col-sm-5 login-box">
					<div class="panel panel-default">
						<div class="panel-intro text-center">
							<h2 class="logo-title">
								<span class="logo-icon"> </span> {{ t('Code') }} <span> </span>
							</h2>
						</div>

						<div class="panel-body">
							<form id="tokenForm" role="form" method="POST" action="{{ lurl(Request::path()) }}">
								{!! csrf_field() !!}

								<!-- Token -->
								<div class="form-group <?php echo (isset($errors) and $errors->has('code')) ? 'has-error' : ''; ?>">
									<label for="code" class="control-label">{{ getTokenLabel() }}:</label>
									<div class="input-icon"><i class="fa icon-lock-2"></i>
										<input id="code" name="code" type="text" placeholder="{{ t('Enter the validation code') }}" class="form-control" value="{{ old('code') }}">
									</div>
								</div>

								<!-- Submit -->
								<div class="form-group">
									<button id="tokenBtn" type="submit" class="btn btn-primary btn-lg btn-block">{{ t('Submit') }}</button>
								</div>
							</form>
						</div>

						<div class="panel-footer">
							<p class="text-center"></p>
							<div style=" clear:both"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			$("#tokenBtn").click(function () {
				$("#tokenForm").submit();
				return false;
			});
		});
	</script>
@endsection
