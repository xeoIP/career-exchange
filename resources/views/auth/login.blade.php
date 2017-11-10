@extends('layouts.master')

@section('content')
	<div class="main-container login-account">
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


				@if (config('settings.activation_social_login'))
					<div class="container text-center" style="margin-bottom: 30px;">
						<div class="row">
							<div class="btn btn-fb" style="width: 194px; margin-right: 1px;">
								<a href="{{ lurl('auth/facebook') }}" class="btn-fb"><i class="icon-facebook"></i> Facebook</a>
							</div>
							<div class="btn btn-danger" style="width: 194px; margin-left: 1px;">
								<a href="{{ lurl('auth/google') }}" class="btn-danger"><i class="icon-googleplus-rect"></i> Google+</a>
							</div>
						</div>
					</div>
				@endif


				<div class="col-sm-5 login-box my-account">
					<form id="loginForm" role="form" method="POST" action="{{ url()->current() }}">
						{!! csrf_field() !!}
						<input type="hidden" name="country" value="{{ $country->get('code') }}">
						<div class="panel panel-default">

							<div class="panel-intro text-center">
								<h2 class="logo-title">
									<span class="logo-icon"></span>{{ t('Log In') }}
								</h2>
							</div>

							<div class="panel-body">
								<?php
									$loginValue = (session()->has('login')) ? session('login') : old('login');
									$loginField = getLoginField($loginValue);
									if ($loginField == 'phone') {
										$loginValue = phoneFormat($loginValue, old('country', $country->get('code')));
									}
								?>
								<!-- Login -->
								<div class="form-group <?php echo (isset($errors) and $errors->has('login')) ? 'has-error' : ''; ?>">
									<div class="input-icon">
										<input id="login" name="login" type="text" placeholder="{{ getLoginLabel() }}" class="form-control input-text" value="{{ $loginValue }}">
									</div>
								</div>

								<!-- Password -->
								<div class="form-group <?php echo (isset($errors) and $errors->has('password')) ? 'has-error' : ''; ?>">
									<div class="input-icon">
										<input id="password" name="password" type="password" class="form-control input-text" placeholder="{{ t('Password') }}">
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
									<button id="loginBtn" class="btn btn-block button border fw margin-top-10"> {{ t('Log In') }} </button>
								</div>
							</div>

							<div class="panel-footer">
								<label class="checkbox pull-left" style="padding-left: 20px;">
									<input type="checkbox" value="1" name="remember" id="remember"> {{ t('Keep me logged in') }} </label>
								<p class="text-center pull-right lost-password"><a href="{{ lurl('password/reset') }}"> {{ t('Lost your password?') }} </a>
								</p>
								<div style=" clear:both"></div>
							</div>
						</div>
					</form>

					<div class="login-box-btm text-center">
						<p>{{ t('Don\'t have an account?') }} <a href="{{ lurl(trans('routes.register')) }}"><strong>{{ t('Apply now') }}!</strong></a></p>
					</div>
				</div>

			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			$("#loginBtn").click(function () {
				$("#loginForm").submit();
				return false;
			});
		});
	</script>
@endsection
