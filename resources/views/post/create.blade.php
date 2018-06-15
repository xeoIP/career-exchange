@extends('layouts.master')

@section('wizard')
	@include('post.inc.wizard')
@endsection

@section('content')
	<div class="main-container">
		<div class="container">
			<div class="row">

				@include('post.inc.notification')

				<div class="col-md-9 page-content">
					<div class="inner-box category-content">
						<h2 class="title-2"><strong> <i class="icon-docs"></i> {{ t('Post a Job') }}</strong></h2>
						<div class="row">
							<div class="col-sm-12">

								<form class="form-horizontal" id="postForm" method="POST" action="{{ url()->current() }}" enctype="multipart/form-data">
									{!! csrf_field() !!}
									<fieldset>

										<!-- parent -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('parent')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label">{{ t('Category') }} <sup>*</sup></label>
											<div class="col-md-8">
												<select name="parent" id="parent" class="form-control selecter">
													<option value="0" data-type=""
															@if(old('parent')=='' or old('parent')==0)selected="selected"@endif> {{ t('Select a category') }} </option>
													@foreach ($categories as $cat)
														<option value="{{ $cat->tid }}" data-type="{{ $cat->type }}"
																@if(old('parent')==$cat->tid)selected="selected"@endif> {{ $cat->name }} </option>
													@endforeach
												</select>
												<input type="hidden" name="parent_type" id="parent_type" value="{{ old('parent_type') }}">
											</div>
										</div>

										<!-- category -->
										<div id="subCatBloc" class="form-group required <?php echo (isset($errors) and $errors->has('category')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label">{{ t('Sub-Category') }} <sup>*</sup></label>
											<div class="col-md-8">
												<select name="category" id="category" class="form-control selecter">
													<option value="0"
															@if(old('category')=='' or old('category')==0)selected="selected"@endif> {{ t('Select a sub-category') }} </option>
												</select>
											</div>
										</div>

										<!-- title -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('title')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="title">{{ t('Title') }} <sup>*</sup></label>
											<div class="col-md-8">
												<input id="title" name="title" placeholder="{{ t('Job title') }}" class="form-control input-md"
													   type="text" value="{{ old('title') }}">
												<span class="help-block">{{ t('A great title needs at least 60 characters.') }} </span>
											</div>
										</div>

										<!-- description -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('description')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="description">{{ t('Description') }} <sup>*</sup></label>
                                            <div class="col-md-11" style="position: relative; float: right; padding-top: 10px;">
                                                <?php $ckeditorClass = (config('settings.ckeditor_wysiwyg')) ? 'ckeditor' : ''; ?>
												<textarea class="form-control {{ $ckeditorClass }}" id="description" name="description" rows="10">{{ old('description') }}</textarea>
												<p class="help-block">{{ t('Describe what makes your ad unique') }}</p>
											</div>
										</div>

										<!-- post_type -->
										<div id="postTypeBloc" class="form-group required <?php echo (isset($errors) and $errors->has('post_type')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label">{{ t('Job Type') }} <sup>*</sup></label>
											<div class="col-md-8">
												<select name="post_type" id="post_type" class="form-control selecter">
													@foreach ($postTypes as $postType)
														<option value="{{ $postType->tid }}" @if(old('post_type')==$postType->tid)selected="selected"@endif> {{ $postType->name }} </option>
													@endforeach
												</select>
											</div>
										</div>

										<!-- salary_min & salary_max -->
										<div id="salaryBloc" class="form-group <?php echo (isset($errors) and $errors->has('salary_min')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="price">{{ t('Salary') }}</label>
											<div class="col-md-4">
												<div class="input-group">
													@if ($country->get('currency')->in_left == 1)
														<span class="input-group-addon">{{ $country->get('currency')->symbol }}</span>
													@endif
													<input id="salary_min" name="salary_min" class="form-control" placeholder="{{ t('Salary (min)') }}" type="text" value="{{ old('salary_min') }}">
													<input id="salary_max" name="salary_max" class="form-control" placeholder="{{ t('Salary (max)') }}" type="text" value="{{ old('salary_max') }}">
													@if ($country->get('currency')->in_left == 0)
														<span class="input-group-addon">{{ $country->get('currency')->symbol }}</span>
													@endif
												</div>
											</div>

											<!-- salary_type -->
											<div class="col-md-4">
												<select name="salary_type" id="salary_type" class="form-control selecter">
													@foreach ($salaryTypes as $salaryType)
														<option value="{{ $salaryType->tid }}" @if(old('post_type')==$salaryType->tid)selected="selected"@endif>
															{{ 'per'.' '.$salaryType->name }}
														</option>
													@endforeach
												</select>
												<div class="checkbox">
													<label>
														<input id="negotiable" name="negotiable" type="checkbox" value="1" {{ (old('negotiable')=='1') ? 'checked="checked"' : '' }}>
														{{ t('Negotiable') }}
													</label>
												</div>
											</div>
										</div>

										<!-- start_date -->
										<div class="form-group <?php echo (isset($errors) and $errors->has('start_date')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="start_date">{{ t('Start Date') }} </label>
											<div class="col-md-8">
												<input id="start_date" name="start_date" placeholder="{{ t('Start Date') }}" class="form-control input-md"
													   type="text" value="{{ old('start_date') }}">
											</div>
										</div>


										<div class="content-subheading">
											<i class="icon-user fa"></i>
											<strong>Company information</strong>
										</div>


										<!-- company_name -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('company_name')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="company_name">{{ t('Company Name') }} <sup>*</sup></label>
											<div class="col-md-8">
												<input id="company_name" name="company_name" placeholder="{{ t('Company Name') }}" class="form-control input-md" type="text" value="{{ old('company_name') }}">
											</div>
										</div>

										<!-- company_description -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('company_description')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="company_description">{{ t('Company Description') }} <sup>*</sup></label>
											<div class="col-md-8">
												<textarea class="form-control" id="company_description" name="company_description" rows="5">{{ old('company_description') }}</textarea>
												<p class="help-block">{{ t('Describe the company') }}</p>
											</div>
										</div>

										<!-- logo -->
										<div id="logoBloc" class="form-group <?php echo (isset($errors) and $errors->has('logo')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="logo"> {{ t('Logo') }} </label>
											<div class="col-md-8">
												<div class="mb10 <?php echo (isset($errors) and $errors->has('logo')) ? 'has-error' : ''; ?>">
													<input id="logo" name="logo" type="file" class="file picimg">
												</div>
												<p class="help-block">{{ t('File types: :file_types', ['file_types' => showValidFileTypes('image')]) }}</p>
											</div>
										</div>

										<!-- company_website -->
										<div class="form-group <?php echo (isset($errors) and $errors->has('company_website')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="company_website">{{ t('Company Website') }} </label>
											<div class="col-md-8">
												<input id="company_website" name="company_website" placeholder="{{ t('Company Website') }}" class="form-control input-md" type="text" value="{{ old('company_website') }}">
											</div>
										</div>

										<!-- country -->
										@if (!$ipCountry)
											<div class="form-group required <?php echo (isset($errors) and $errors->has('country')) ? 'has-error' : ''; ?>">
												<label class="col-md-3 control-label" for="country">{{ t('Your Country') }} <sup>*</sup></label>
												<div class="col-md-8">
													<select id="country" name="country" class="form-control sselecter">
														<option value="0" {{ (!old('country') or old('country')==0) ? 'selected="selected"' : '' }}> {{ t('Select your Country') }} </option>
														@foreach ($countries as $item)
															<option value="{{ $item->get('code') }}" {{ (old('country', ($country) ? $country->get('code') : 0)==$item->get('code')) ? 'selected="selected"' : '' }}>{{ $item->get('name') }}</option>
														@endforeach
													</select>
												</div>
											</div>
										@else
											<input id="country" name="country" type="hidden" value="{{ $country->get('code') }}">
										@endif

										<?php
										/*
										@if (\Illuminate\Support\Facades\Schema::hasColumn('posts', 'address'))
										<!-- Address -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('address')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="title">{{ t('Address') }} </label>
											<div class="col-md-8">
												<input id="address" name="address" placeholder="{{ t('Address') }}" class="form-control input-md"
													   type="text" value="{{ old('address') }}">
												<span class="help-block">{{ t('Fill an address to display on Google Maps.') }} </span>
											</div>
										</div>
										@endif
										*/
										?>

										<!-- contact_name -->
										@if (Auth::check())
											<input id="contact_name" name="contact_name" type="hidden" value="{{ $user->name }}">
										@else
											<div class="form-group required <?php echo (isset($errors) and $errors->has('contact_name')) ? 'has-error' : ''; ?>">
												<label class="col-md-3 control-label" for="contact_name">{{ t('Contact Name') }} <sup>*</sup></label>
												<div class="col-md-8">
													<div class="input-group">
														<span class="input-group-addon"><i class="icon-user"></i></span>
														<input id="contact_name" name="contact_name" placeholder="{{ t('Contact Name') }}"
														   class="form-control input-md" type="text" value="{{ old('contact_name') }}">
													</div>
												</div>
											</div>
										@endif

										<!-- email -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('email')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="email"> {{ t('Contact Email') }} <sup>*</sup></label>
											<div class="col-md-8">
												<div class="input-group">
													<span class="input-group-addon">
														<i class="icon-mail"></i>
													</span>
													<input id="email" name="email" class="form-control"
														   placeholder="{{ t('Email') }}" type="text"
														   value="{{ old('email', ((Auth::check() and isset($user->email)) ? $user->email : '')) }}">
												</div>
											</div>
										</div>

										<?php
											if (Auth::check()) {
												$formPhone = ($user->country_code == config('country.code')) ? $user->phone : '';
											} else {
												$formPhone = '';
											}
										?>
										<!-- phone -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('phone')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="phone">{{ t('Phone Number') }}</label>
											<div class="col-md-8">
												<div class="input-group">
													<span id="phoneCountry" class="input-group-addon">{!! getPhoneIcon(config('country.code')) !!}</span>
													<input id="phone" name="phone"
														   placeholder="{{ t('Phone Number') }}"
														   class="form-control input-md" type="text"
														   value="{{ phoneFormat(old('phone', $formPhone), old('country', config('country.code'))) }}">
												</div>
												<div class="checkbox">
													<label>
														<input id="phone_hidden" name="phone_hidden" type="checkbox"
															   value="1" {{ (old('phone_hidden')=='1') ? 'checked="checked"' : '' }}>
														<small> {{ t('Hide the phone number on this ads.') }}</small>
													</label>
												</div>
											</div>
										</div>

										@if (config('country.admin_field_active') == 1 and in_array(config('country.admin_type'), ['1', '2']))
										<!-- admin_code -->
										<div id="locationBox" class="form-group required <?php echo (isset($errors) and $errors->has('admin_code')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="location">{{ t('Location') }} <sup>*</sup></label>
											<div class="col-md-8">
												<select id="adminCode" name="admin_code" class="form-control sselecter">
													<option value="0" {{ (!old('admin_code') or old('admin_code')==0) ? 'selected="selected"' : '' }}>
														{{ t('Select your Location') }}
													</option>
												</select>
											</div>
										</div>
										@endif

										<!-- city -->
										<div id="cityBox" class="form-group required <?php echo (isset($errors) and $errors->has('city')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label" for="city">{{ t('City') }} <sup>*</sup></label>
											<div class="col-md-8">
												<select id="city" name="city" class="form-control sselecter">
													<option value="0" {{ (!old('city') or old('city')==0) ? 'selected="selected"' : '' }}>
														{{ t('Please select your location before') }}
													</option>
												</select>
											</div>
										</div>


										@if (config('settings.activation_recaptcha'))
                                            <!-- g-recaptcha-response -->
											<div class="form-group required <?php echo (isset($errors) and $errors->has('g-recaptcha-response')) ? 'has-error' : ''; ?>">
												<label class="col-md-3 control-label" for="g-recaptcha-response"></label>
												<div class="col-md-8">
													{!! Recaptcha::render(['lang' => config('app.locale')]) !!}
												</div>
											</div>
										@endif

										<!-- term -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('term')) ? 'has-error' : ''; ?>">
											<label class="col-md-3 control-label"></label>
											<div class="col-md-8">
												<label class="checkbox-inline" for="term-0" style="margin-left: -20px;">
													{!! t('By continuing on this website, you accept our <a href=":url">Terms of Use</a>', ['url' => getUrlPageByType('terms')]) !!}
												</label>
											</div>
										</div>

										<!-- Button  -->
										<div class="form-group">
											<label class="col-md-3 control-label"></label>
											<div class="col-md-8">
												<button id="submitPostForm" class="btn btn-success btn-lg submitPostForm"> {{ t('Submit') }} </button>
											</div>
										</div>

										<div style="margin-bottom: 30px;"></div>

									</fieldset>
								</form>


							</div>
						</div>
					</div>
				</div>
				<!-- /.page-content -->

				<div class="col-md-3 reg-sidebar">
					<div class="reg-sidebar-inner text-center">
						<div class="promo-text-box"><i class=" icon-picture fa fa-4x icon-color-1"></i>
							<h3><strong>{{ t('Post a Job') }}</strong></h3>
							<p>
								{{ t('Do you have a post to be filled within your company? Find the right candidate in a few clicks at :app_name', ['app_name' => getDomain()]) }}
							</p>
						</div>

						<div class="panel sidebar-panel">
							<div class="panel-heading uppercase">
								<small><strong>{{ t('How to find quickly a candidate?') }}</strong></small>
							</div>
							<div class="panel-content">
								<div class="panel-body text-left">
									<ul class="list-check">
										<li> {{ t('Use a brief title and description of the ad') }} </li>
										<li> {{ t('Make sure you post in the correct category') }}</li>
										<li> {{ t('Add a logo to your ad') }}</li>
										<li> {{ t('Put a min and max salary') }}</li>
										<li> {{ t('Check the ad before publish') }}</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_styles')
    @include('layouts.inc.tools.wysiwyg.css')
	<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@endsection

@section('after_scripts')
    @include('layouts.inc.tools.wysiwyg.js')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>
	@if (file_exists(public_path() . '/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js'))
		<script src="{{ url('assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js') }}" type="text/javascript"></script>
	@endif

	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
	@if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js'))
		<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js') }}" type="text/javascript"></script>
	@endif
	<script>
		/* initialize with defaults (logo) */
		$('#logo').fileinput(
		{
			'showPreview': true,
			'allowedFileExtensions': {!! getUploadFileTypes('image', true) !!},
			'browseLabel': '{!! t("Browse") !!}',
			'showUpload': false,
			'showRemove': false,
			'maxFileSize': {{ (int)config('settings.upload_max_file_size', 1000) }}
		});
	</script>
	<script>
		/* Translation */
		var lang = {
			'select': {
				'category': "{{ t('Select a category') }}",
                'subCategory': "{{ t('Select a sub-category') }}",
				'country': "{{ t('Select a country') }}",
				'admin': "{{ t('Select a location') }}",
				'city': "{{ t('Select a city') }}"
			},
			'price': "{{ t('Price') }}",
			'salary': "{{ t('Salary') }}",
            'nextStepBtnLabel': {
                'next': "{{ t('Next') }}",
                'submit': "{{ t('Submit') }}"
            }
		};

		/* Categories */
        var category = {{ old('parent', 0) }};
        var categoryType = '{{ old('parent_type') }}';
        if (categoryType=='') {
            var selectedCat = $('select[name=parent]').find('option:selected');
            categoryType = selectedCat.data('type');
        }
        var subCategory = {{ old('category', 0) }};

		/* Locations */
        var countryCode = '{{ old('country', config('country.code', 0)) }}';
        var adminType = '{{ config('country.admin_type', 0) }}';
        var selectedAdminCode = '{{ old('admin_code', (isset($admin) ? $admin->code : 0)) }}';
        var cityId = '{{ old('city', (isset($post) ? $post->city_id : 0)) }}';

		/* Packages */
        var packageIsEnabled = false;
		@if (isset($packages) and isset($paymentMethods) and $packages->count() > 0 and $paymentMethods->count() > 0)
            packageIsEnabled = true;
        @endif
	</script>

	<script src="{{ url('assets/js/app/d.select.category.js?v=' . vTime()) }}"></script>
	<script src="{{ url('assets/js/app/d.select.location.js?v=' . vTime()) }}"></script>
@endsection
