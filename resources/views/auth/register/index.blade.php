@extends('layouts.master')

@section('content')
	<div class="main-container login-account">
		<div class="container">
			<div class="row">

				@if (isset($errors) and count($errors) > 0)
					<div class="col-lg-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5>
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

				<div class="col-md-8 page-content">
					<div class="inner-box category-content">
						<h2 class="title-2"><strong> <i class="icon-user-add"></i> {{ t('Create your account, Its free') }}</strong></h2>
						<div class="row">
							@if (config('settings.activation_social_login'))
								<div class="container text-center" style="margin-bottom: 30px;">
									<div class="row row-centered">
										<div class="btn btn-lg btn-fb col-md-5 col-centered" style="margin-right: 4px;">
											<a href="{{ lurl('auth/facebook') }}" class="btn-fb"><i class="icon-facebook"></i> {!! t('Connect with Facebook') !!}</a>
										</div>
										<div class="btn btn-lg btn-danger col-md-5 col-centered" style="margin-left: 4px;">
											<a href="{{ lurl('auth/google') }}" class="btn-danger"><i class="icon-googleplus-rect"></i> {!! t('Connect with Google+') !!}</a>
										</div>
									</div>

									<div class="row row-centered loginOr">
										<div class="col-xs-12 col-sm-12">
											<hr class="hrOr">
											<span class="spanOr rounded">{{ t('or') }}</span>
										</div>
									</div>
								</div>
							@endif
							<div class="col-sm-12">
								<form id="signupForm" class="form-horizontal" method="POST" action="{{ url()->current() }}" enctype="multipart/form-data">
									{!! csrf_field() !!}
									<fieldset>
										<?php
										/*
										<!-- gender -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('gender')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label">{{ t('Gender') }} <sup>*</sup></label>
											<div class="col-md-7">
												<select name="gender" id="gender" class="form-control selecter">
													<option value="0"
															@if(old('gender')=='' or old('gender')==0)selected="selected"@endif> {{ t('Select') }} </option>
													@foreach ($genders as $gender)
														<option value="{{ $gender->tid }}" @if(old('gender')==$gender->tid)selected="selected"@endif>
															{{ $gender->name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
										*/
										?>

										<!-- name -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('name')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label">{{ t('Name') }} <sup>*</sup></label>
											<div class="col-md-7">
												<input name="name" placeholder="{{ t('Name') }}" class="form-control input-md" type="text" value="{{ old('name') }}">
											</div>
										</div>

										<!-- user_type -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('user_type')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label">{{ t('You are a') }} <sup>*</sup></label>
											<div class="col-md-7">
												@foreach ($userTypes as $type)
													<label class="radio-inline" for="user_type-{{ $type->id }}">
														<input type="radio" name="user_type" id="userType-{{ $type->id }}" class="user-type"
															   value="{{ $type->id }}" {{ (old('user_type', \Illuminate\Support\Facades\Input::get('type'))==$type->id) ? 'checked="checked"' : '' }}>
														{{ t('' . $type->name) }}
													</label>
												@endforeach
											</div>
										</div>

										<!-- country -->
										@if (!$ipCountry)
											<div class="form-group required <?php echo (isset($errors) and $errors->has('country')) ? 'has-error' : ''; ?>">
												<label class="col-md-3 control-label" for="country">{{ t('Your Country') }} <sup>*</sup></label>
												<div class="col-md-7">
													<select id="country" name="country" class="form-control sselecter">
														<option value="0" {{ (!old('country') or old('country')==0) ? 'selected="selected"' : '' }}>{{ t('Select') }}</option>
														@foreach ($countries as $code => $item)
															<option value="{{ $code }}" {{ (old('country', (!$country->isEmpty()) ? $country->get('code') : 0)==$code) ? 'selected="selected"' : '' }}>
																{{ $item->get('name') }}
															</option>
														@endforeach
													</select>
												</div>
											</div>
										@else
											<input id="country" name="country" type="hidden" value="{{ $country->get('code') }}">
										@endif

										@if (isEnabledField('phone'))
										<!-- phone -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('phone')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label">{{ t('Phone') }}
												@if (!isEnabledField('email'))
													<sup>*</sup>
												@endif
											</label>
											<div class="col-md-7">
												<div class="input-group">
													<span id="phoneCountry" class="input-group-addon">{!! getPhoneIcon(old('country', $country->get('code'))) !!}</span>
													<input name="phone" placeholder="{{ t('Phone Number') }}"
														   class="form-control input-md" type="text" value="{{ phoneFormat(old('phone'), old('country', $country->get('code'))) }}">
												</div>
											</div>
										</div>
										@endif

										@if (isEnabledField('email'))
										<!-- email -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('email')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="email">{{ t('Email') }}
												@if (!isEnabledField('phone'))
													<sup>*</sup>
												@endif
											</label>
											<div class="col-md-7">
												<div class="input-group">
													<span class="input-group-addon"><i class="icon-mail"></i></span>
													<input id="email" name="email" type="email" class="form-control" placeholder="{{ t('Email') }}" value="{{ old('email') }}">
												</div>
											</div>
										</div>
										@endif

										@if (isEnabledField('username'))
										<!-- username -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('username')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="email">{{ t('Username') }}</label>
											<div class="col-md-7">
												<div class="input-group">
													<span class="input-group-addon"><i class="icon-user"></i></span>
													<input id="username" name="username" type="text" class="form-control" placeholder="{{ t('Username') }}" value="{{ old('username') }}">
												</div>
											</div>
										</div>
										@endif

										<!-- password -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('password')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="password">{{ t('Password') }} <sup>*</sup></label>
											<div class="col-md-7">
												<input id="password" name="password" type="password" class="form-control" placeholder="{{ t('Password') }}">
												<br>
												<input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="{{ t('Password Confirmation') }}">
												<p class="help-block">{{ t('At least 5 characters') }}</p>
											</div>
										</div>

										<!-- filename -->
										<div id="resumeBloc" class="form-group required <?php echo (isset($errors) and $errors->has('filename')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="filename"> {{ t('Your resume') }} </label>
											<div class="col-md-7">
												<div class="mb10">
													<input id="filename" name="filename" type="file" class="file">
												</div>
												<p class="help-block">{{ t('File types: :file_types', ['file_types' => showValidFileTypes('file')]) }}</p>
											</div>
										</div>

										@if (config('settings.activation_recaptcha'))
											<!-- g-recaptcha-response -->
											<div class="form-group required <?php echo (isset($errors) and $errors->has('g-recaptcha-response')) ? 'has-error' : ''; ?>">
												<label class="col-md-3 control-label" for="g-recaptcha-response"></label>
												<div class="col-md-7">
													{!! Recaptcha::render(['lang' => config('app.locale')]) !!}
												</div>
											</div>
										@endif

										<!-- term -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('term')) ? 'has-error' : ''; ?>"
											 style="margin-top: -10px;">
											<label class="col-md-3 control-label"></label>
											<div class="col-md-7">
												<div class="termbox mb10">
													<label class="checkbox-inline" for="term">
														<input name="term" id="term" value="1" type="checkbox" {{ (old('term')=='1') ? 'checked="checked"' : '' }}>
														{!! t('I have read and agree to the <a href=":url">Terms & Conditions</a>', ['url' => getUrlPageByType('terms')]) !!}
													</label>
												</div>
												<div style="clear:both"></div>
											</div>
										</div>

										<!-- Button  -->
										<div class="form-group">
											<label class="col-md-3 control-label"></label>
											<div class="col-md-9">
												<button id="signupBtn" class="button"> {{ t('Register') }} </button>
											</div>
										</div>

										<div style="margin-bottom: 30px;"></div>

									</fieldset>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 reg-sidebar">
					<div class="reg-sidebar-inner text-center">
						<div class="promo-text-box"><i class=" icon-picture fa fa-4x icon-color-1"></i>
							<h3><strong>{{ t('Post a Job') }}</strong></h3>
							<p>
								{{ t('Do you have a post to be filled within your company? Find the right candidate in a few clicks at :app_name',
								['app_name' => getDomain()]) }}
							</p>
						</div>
						<div class="promo-text-box"><i class="icon-pencil-circled fa fa-4x icon-color-2"></i>
							<h3><strong>{{ t('Create and Manage Jobs') }}</strong></h3>
							<p>{{ t('Become a best company. Create and Manage your jobs. Repost your old jobs, etc.') }}</p>
						</div>
						<div class="promo-text-box"><i class="icon-heart-2 fa fa-4x icon-color-3"></i>
							<h3><strong>{{ t('Create your Favorite jobs list.') }}</strong></h3>
							<p>{{ t('Create your Favorite jobs list, and save your searchs. Don\'t forget any opportunity!') }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_styles')
	<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
	<style>
		.krajee-default.file-preview-frame:hover:not(.file-preview-error) {
			box-shadow: 0 0 5px 0 #666666;
		}
	</style>
@endsection

@section('after_scripts')
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
	@if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js'))
		<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js') }}" type="text/javascript"></script>
	@endif

	<script>
		/* initialize with defaults (resume) */
		$('#filename').fileinput(
		{
            language: '{{ config('app.locale') }}',
			showPreview: false,
			allowedFileExtensions: {!! getUploadFileTypes('file', true) !!},
			showUpload: false,
			showRemove: false,
			maxFileSize: {{ (int)config('settings.upload_max_file_size', 1000) }}
		});
	</script>
	<script>
		var userType = '<?php echo old('user_type', \Illuminate\Support\Facades\Input::get('type')); ?>';

		$(document).ready(function ()
		{
			/* Set user type */
			setUserType(userType);
			$('.user-type').click(function () {
				setUserType($(this).val());
			});

			/* Submit Form */
			$("#signupBtn").click(function () {
				$("#signupForm").submit();
				return false;
			});
		});
	</script>
@endsection
