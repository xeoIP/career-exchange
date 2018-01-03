@extends('layouts.master')

<?php
// Phone
$phone = TextToImage::make($post->phone, IMAGETYPE_PNG, ['backgroundColor' => '#2ECC71', 'color' => '#FFFFFF']);
?>

@section('content')
	{!! csrf_field() !!}
	<input type="hidden" id="post_id" value="{{ $post->id }}">
	<div class="main-container">

		@if (Session::has('flash_notification'))
			<div class="container" style="margin-bottom: -10px; margin-top: -10px;">
				<div class="row">
					<div class="col-lg-12">
						@include('flash::message')
					</div>
				</div>
			</div>
			<?php Session::forget('flash_notification.message'); ?>
		@endif

		@include('layouts.inc.advertising.top')

		<div class="container">
			<ol class="breadcrumb pull-left">
				<li><a href="{{ lurl('/') }}"><i class="icon-home fa"></i></a></li>
				<li><a href="{{ lurl('/') }}">{{ $country->get('name') }}</a></li>
				<li>
					<a href="{{ lurl(trans('routes.v-search-cat', ['countryCode' => $country->get('icode'), 'catSlug' => $parentCat->slug])) }}">
						{{ $parentCat->name }}
					</a>
				</li>
				@if ($parentCat->id != $cat->id)
				<li>
					<a href="{{ lurl(trans('routes.v-search-subCat',
					[
					'countryCode' => $country->get('icode'),
					'catSlug'     => $parentCat->slug,
					'subCatSlug'  => $cat->slug
					])) }}">
						{{ $cat->name }}
					</a>
				</li>
				@endif
				<li class="active">{{ str_limit($post->title, 70) }}</li>
			</ol>
			<div class="pull-right backtolist">
				<a href="{{ URL::previous() }}">
					<i class="fa fa-angle-double-left"></i> {{ t('Back to Results') }}
				</a>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-sm-9 page-content col-thin-right">
					<div class="inner inner-box ads-details-wrapper">
						<h2 class="enable-long-words">
							<strong>
                                <a href="{{ lurl(slugify($post->title).'/'.$post->id.'.html') }}" title="{{ mb_ucfirst($post->title) }}">
                                    {{ mb_ucfirst($post->title) }}
                                </a>
                            </strong>
							<small class="label label-default adlistingtype">{{ t(':type Job', ['type' => $postType->name]) }}</small>
                            @if ($post->featured==1 and isset($package) and !empty($package))
								<i class="icon-ok-circled tooltipHere" style="color: {{ $package->ribbon }};" title="" data-placement="right"
								   data-toggle="tooltip" data-original-title="{{ $package->short_name }}"></i>
                            @endif
						</h2>
						<span class="info-row">
							<span class="date"><i class=" icon-clock"> </i> {{ $post->created_at_ta }} </span> -&nbsp;
							<span class="category">{{ $parentCat->name }}</span> -&nbsp;
							<span class="item-location"><i class="fa fa-map-marker"></i> {{ $post->city->name }} </span> -&nbsp;
							<span class="category"><i class="icon-eye-3"></i> {{ $post->visits }} {{ trans_choice('global.count_views', getPlural($post->visits)) }}</span>
						</span>

						<div class="ads-details">
							<div class="row" style="padding-bottom: 20px;">
								<div class="ads-details-info jobs-details-info col-md-8 enable-long-words from-wysiwyg">
									<h5 class="list-title"><strong>{{ t('Job Details') }}</strong></h5>
                                    <div>
                                        @if (config('settings.simditor_wysiwyg') || config('settings.ckeditor_wysiwyg'))
                                            {!! auto_link(\Mews\Purifier\Facades\Purifier::clean($post->description)) !!}
                                        @else
                                            {!! nl2br(auto_link(str_clean($post->description))) !!}
                                        @endif
                                    </div>
									@if (!empty($post->company_description))
										<div style="margin-bottom: 50px;"></div>
										<h5 class="list-title"><strong>{{ t('Company Description') }}</strong></h5>
                                        <div>
										    {!! nl2br(auto_link(str_clean($post->company_description))) !!}
                                        </div>
									@endif
								</div>
								<div class="col-md-4">
									<aside class="panel panel-body panel-details job-summery">
										<ul>
											@if (!empty($post->start_date))
											<li>
												<p class="no-margin">
													<strong>{{ t('Start Date') }}:</strong>&nbsp;
													{{ $post->start_date }}
												</p>
											</li>
											@endif
											<li>
												<p class="no-margin">
													<strong>{{ t('Company') }}:</strong>&nbsp;
													<a href="{!! lurl(trans('routes.v-search-company', ['companyName' => $post->company_name])) !!}">
														{{ $post->company_name }}
													</a>
												</p>
											</li>
											<li>
												<p class="no-margin">
													<strong>{{ t('Salary') }}:</strong>&nbsp;
													@if ($post->salary_max > 0)
														{!! \App\Helpers\Number::money($post->salary_max) !!}
													@else
														{!! \App\Helpers\Number::money('--') !!}
													@endif
													@if ($post->negotiable == 1)
														<small class="label label-success"> {{ t('Negotiable') }}</small>
													@endif
												</p>
											</li>
											<li>
												<p class="no-margin">
													<strong>{{ t('Job Type') }}:</strong>&nbsp;
													<a href="{{ lurl(trans('routes.t-search')) . '?type[]=' . $post->post_type_id }}">
														{{ \App\Models\PostType::find($post->post_type_id)->name }}
													</a>
												</p>
											</li>
											<li>
												<p class="no-margin">
													<strong>{{ t('Location') }}:</strong>&nbsp;
													<a href="{!! lurl(trans('routes.v-search-city', ['countryCode' => $country->get('icode'), 'city' => slugify($post->city->name), 'id' => $post->city->id])) !!}">
														{{ $post->city->name }}
													</a>
												</p>
											</li>
										</ul>
									</aside>
									<div class="ads-action">
										<ul class="list-border">
											@if (isset($post->user) and $post->user->id != 1)
												<li>
													<a href="{{ lurl(trans('routes.v-search-user', ['countryCode' => $country->get('icode'), 'id' => $post->user->id])) }}">
														<i class="fa fa-user"></i> {{ t('More jobs by Company') }}
													</a>
												</li>
											@endif
											<li id="{{ $post->id }}">
												<a class="make-favorite">
												@if (Auth::check())
													@if (\App\Models\SavedPost::where('user_id', $user->id)->where('post_id', $post->id)->count() > 0)
														<i class="fa fa-heart"></i> {{ t('Saved Job') }}
													@else
														<i class="fa fa-heart-o"></i> {{ t('Save Job') }}
													@endif
												@else
													<i class="fa fa-heart-o"></i> {{ t('Save Job') }}
												@endif
                                                </a>
											</li>
											<li>
												<a href="{{ lurl('posts/' . $post->id . '/report') }}">
													<i class="fa icon-info-circled-alt"></i> {{ t('Report abuse') }}
												</a>
											</li>
										</ul>
									</div>
								</div>

								<br>&nbsp;<br>
							</div>
							<div class="content-footer text-left">
								@if (Auth::check())
									@if ($user->id == $post->user_id)
										<a class="btn btn-default" href="{{ lurl('posts/'.$post->id.'/edit') }}">
											<i class="fa fa-pencil-square-o"></i> {{ t('Edit') }}
										</a>
									@else
										@if ($post->email != '')
											<a class="btn btn-default" data-toggle="modal" href="#applyJob">
												<i class="icon-mail-2"></i> {{ t('Apply Online') }}
											</a>
										@endif
									@endif
								@else
									@if ($post->email != '')
										<a class="btn btn-default" data-toggle="modal" href="#applyJob">
											<i class="icon-mail-2"></i> {{ t('Apply Online') }}
										</a>
									@endif
								@endif
								@if ($post->phone_hidden != 1 and !empty($post->phone))
									<a href="tel:{{ $post->phone }}" class="btn btn-success showphone">
										<i class="icon-phone-1"></i>
										{!! $phone !!}{{-- t('View phone') --}}
									</a>
								@endif
							</div>
						</div>
					</div>
					<!--/.ads-details-wrapper-->
				</div>
				<!--/.page-content-->

				<div class="col-sm-3 page-sidebar-right">
					<aside>
						<div class="panel sidebar-panel panel-contact-seller">
							<div class="panel-heading">{{ t('Company Information') }}</div>
							<div class="panel-content user-info">
								<div class="panel-body text-center">
									<div class="seller-info">
										<div class="company-logo-thumb">
											<a>
												<img alt="Logo {{ $post->company_name }}" class="img-responsive" src="{{ resize($post->logo, 'medium') }}">
											</a>
										</div>

										@if (isset($post->company_name) and $post->company_name != '')
											@if (isset($post->user) and $post->user->id != 1)
												<h3 class="no-margin">
													<a href="{{ lurl(trans('routes.v-search-user', ['countryCode' => $country->get('icode'), 'id' => $post->user->id])) }}">
														{{ $post->company_name }}
													</a>
												</h3>
											@else
												<h3 class="no-margin">{{ $post->company_name }}</h3>
											@endif
										@endif
										<p>
											{{ t('Location') }}:&nbsp;
											<strong>
												<a href="{!! lurl(trans('routes.v-search-city', ['countryCode' => $country->get('icode'), 'city' => slugify($post->city->name), 'id' => $post->city->id])) !!}">
													{{ $post->city->name }}
												</a>
											</strong>
										</p>
										@if ($post->user and !empty($post->user->created_at_ta))
											<p> {{ t('Joined') }}: <strong>{{ $post->user->created_at_ta }}</strong></p>
										@endif
										@if (!empty($post->company_website))
											<p>
												{{ t('Web') }}:
												<strong>
													<a href="{{ $post->company_website }}" target="_blank" rel="nofollow">
														{{ getHostByUrl($post->company_website) }}
													</a>
												</strong>
											</p>
										@endif
									</div>
									<div class="user-ads-action">
										@if (Auth::check())
											@if ($user->id == $post->user_id)
												<a href="{{ lurl('posts/'.$post->id.'/edit') }}" data-toggle="modal" class="btn btn-default btn-block">
													<i class="fa fa-pencil-square-o"></i> {{ t('Edit') }}
												</a>
											@else
												@if ($post->email != '')
													<a href="#applyJob" data-toggle="modal" class="btn btn-default btn-block">
														<i class="icon-mail-2"></i> {{ t('Apply Online') }}
													</a>
												@endif
											@endif
										@else
											@if ($post->email != '')
												<a href="#applyJob" data-toggle="modal" class="btn btn-default btn-block">
													<i class="icon-mail-2"></i> {{ t('Apply Online') }}
												</a>
											@endif
										@endif
										@if ($post->phone_hidden != 1 and !empty($post->phone))
											<a href="tel:{{ $post->phone }}" class="btn btn-success btn-block showphone">
												<i class="icon-phone-1"></i>
                                                {!! $phone !!}{{-- t('View phone') --}}
											</a>
										@endif
									</div>
								</div>
							</div>
						</div>

						@if (config('settings.show_post_on_googlemap'))
							<div class="panel sidebar-panel">
								<div class="panel-heading">{{ t('Location\'s Map') }}</div>
								<div class="panel-content">
									<div class="panel-body text-left" style="padding: 0;">
										<div class="ads-googlemaps">
											<iframe id="googleMaps" width="100%" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src=""></iframe>
										</div>
									</div>
								</div>
							</div>
						@endif

						@if (isVerifiedPost($post))
							@include('layouts.inc.social.horizontal')
						@endif

						<div class="panel sidebar-panel">
							<div class="panel-heading">{{ t('Tips for candidates') }}</div>
							<div class="panel-content">
								<div class="panel-body text-left">
									<ul class="list-check">
										<li> {{ t('Check if the offer matches your profile') }} </li>
                                        <li> {{ t('Check the start date') }} </li>
										<li> {{ t('Meet the employer in a professional location') }} </li>
									</ul>
                                    <?php $tipsUrl = getUrlPageByType('tips'); ?>
                                    @if ($tipsUrl != '#' && $tipsUrl != '')
									<p>
										<a class="pull-right" href="{{ $tipsUrl }}">
											{{ t('Know more') }}
											<i class="fa fa-angle-double-right"></i>
										</a>
									</p>
                                    @endif
								</div>
							</div>
						</div>
					</aside>
				</div>
			</div>

			<div style="margin-top: 30px;"></div>

			@include('home.inc.featured')
			@include('layouts.inc.advertising.bottom')
			@if (isVerifiedPost($post))
				@include('layouts.inc.tools.facebook-comments')
			@endif

		</div>
	</div>
@endsection

@section('modal_message')
	@include('post.inc.compose-message')
@endsection

@section('after_styles')
@endsection

@section('after_scripts')
    @if (config('services.googlemaps.key'))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.googlemaps.key') }}" type="text/javascript"></script>
    @endif

	<script>
		/* Favorites Translation */
        var lang = {
            labelSavePostSave: "{!! t('Save Job') !!}",
            labelSavePostRemove: "{{ t('Saved Job') }}",
            loginToSavePost: "{!! t('Please log in to save the Ads.') !!}",
            loginToSaveSearch: "{!! t('Please log in to save your search.') !!}",
            confirmationSavePost: "{!! t('Post saved in favorites successfully !') !!}",
            confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully !') !!}",
            confirmationSaveSearch: "{!! t('Search saved successfully !') !!}",
            confirmationRemoveSaveSearch: "{!! t('Search deleted successfully !') !!}"
        };

		$(document).ready(function () {
			@if (config('settings.show_post_on_googlemap'))
				/* Google Maps */
				getGoogleMaps(
				'{{ config('services.googlemaps.key') }}',
				'{{ (isset($post->city) and !empty($post->city)) ? addslashes($post->city->name) . ',' . $country->get('name') : $country->get('name') }}',
				'{{ config('app.locale') }}'
				);
			@endif
		})
	</script>
@endsection
