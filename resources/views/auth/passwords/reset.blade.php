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
								<span class="logo-icon"> </span> {{ t('Reset Password') }} <span> </span>
							</h2>
						</div>

						<div class="panel-body">
							<form method="POST" action="{{ lurl('password/reset') }}">
								{!! csrf_field() !!}
								<input type="hidden" name="token" value="{{ $token }}">

								<!-- Login -->
								<div class="form-group <?php echo (isset($errors) and $errors->has('login')) ? 'has-error' : ''; ?>">
									<label for="login" class="control-label">{{ t('Login') . ' (' . getLoginLabel() . ')' }}:</label>
									<input type="text" name="login" value="{{ old('login') }}" placeholder="{{ getLoginLabel() }}" class="form-control">
								</div>

								<!-- Password -->
								<div class="form-group <?php echo (isset($errors) and $errors->has('password')) ? 'has-error' : ''; ?>">
									<label for="password" class="control-label">{{ t('Password') }}:</label>
									<input type="password" name="password" placeholder="" class="form-control email">
								</div>

								<!-- Confirmation -->
								<div class="form-group <?php echo (isset($errors) and $errors->has('password_confirmation')) ? 'has-error' : ''; ?>">
									<label for="password_confirmation" class="control-label">{{ t('Password Confirmation') }}:</label>
									<input type="password" name="password_confirmation" placeholder="" class="form-control email">
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
									<button type="submit" class="btn btn-primary btn-lg btn-block">{{ t('Reset the Password') }}</button>
								</div>
							</form>
						</div>

						<div class="panel-footer">
							<p class="text-center "><a href="{{ lurl(trans('routes.login')) }}"> {{ t('Back to the Log In page') }} </a></p>
							<div style=" clear:both"></div>
						</div>
					</div>
					<div class="login-box-btm text-center">
						<p>{{ t('Don\'t have an account?') }} <br><a href="{{ lurl(trans('routes.register')) }}"><strong>{{ t('Sign Up !') }}</strong> </a></p>
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
