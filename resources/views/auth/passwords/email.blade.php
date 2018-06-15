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

				@if (session('status'))
					<div class="col-lg-12">
						<div class="alert alert-success">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('status') }}</p>
						</div>
					</div>
				@endif

				@if (session('email'))
					<div class="col-lg-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('email') }}</p>
						</div>
					</div>
				@endif

				@if (session('phone'))
					<div class="col-lg-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('phone') }}</p>
						</div>
					</div>
				@endif

				@if (session('login'))
					<div class="col-lg-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('login') }}</p>
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

				<div class="col-sm-5 login-box">
					<div class="panel panel-default">
						<div class="panel-intro text-center">
							<h2 class="logo-title">
								<span class="logo-icon"> </span> {{ t('Password') }} <span> </span>
							</h2>
						</div>

						<div class="panel-body">
							<form id="pwdForm" role="form" method="POST" action="{{ lurl('password/email') }}">
								{!! csrf_field() !!}

								<!-- Login -->
								<div class="form-group <?php echo (isset($errors) and $errors->has('login')) ? 'has-error' : ''; ?>">
									<label for="login" class="control-label">{{ t('Login') . ' (' . getLoginLabel() . ')' }}:</label>
									<div class="input-icon"><i class="icon-user fa"></i>
										<input id="login" name="login" type="text" placeholder="{{ getLoginLabel() }}" class="form-control" value="{{ old('login') }}">
									</div>
								</div>

								@if (config('settings.activation_recaptcha'))
									<!-- g-recaptcha-response -->
									<div class="form-group required <?php echo (isset($errors) and $errors->has('g-recaptcha-response')) ? 'has-error' : ''; ?>">
										<div class="no-label">
											{!! Recaptcha::render(['lang' => config('app.locale')]) !!}
										</div>
									</div>
								@endif

								<!-- Submit -->
								<div class="form-group">
									<button id="pwdBtn" type="submit" class="btn btn-primary btn-lg btn-block">{{ t('Submit') }}</button>
								</div>
							</form>
						</div>

						<div class="panel-footer">
							<p class="text-center"><a href="{{ lurl(trans('routes.login')) }}"> {{ t('Back to the Log In page') }} </a></p>
							<div style=" clear:both"></div>
						</div>
					</div>
					<div class="login-box-btm text-center">
						<p> {{ t('Don\'t have an account?') }} <br>
							<a href="{{ lurl(trans('routes.register')) }}"><strong>{{ t('Sign Up !') }}</strong> </a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			$("#pwdBtn").click(function () {
				$("#pwdForm").submit();
				return false;
			});
		});
	</script>
@endsection
