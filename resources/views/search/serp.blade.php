<?php
	$fullUrl = url(\Illuminate\Support\Facades\Request::getRequestUri());
    $tmpExplode = explode('?', $fullUrl);
    $fullUrlNoParams = current($tmpExplode);
?>
@extends('layouts.master')

@section('search')
	@parent
	@include('search.inc.form')
	@include('search.inc.breadcrumbs')
	@include('layouts.inc.advertising.top')
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
			</div>

			<div class="row">

				@include('search.inc.sidebar')

				<div class="col-sm-9 page-content col-thin-left">

					<div class="category-list">
						<div class="tab-box clearfix">

							<!-- Nav tabs -->
							<div class="col-lg-12 box-title no-border">
								<div class="inner">
									<h2>
										<small>{{ $count->get('all') }} {{ t('Jobs Found') }}</small>
									</h2>
								</div>
							</div>

							<!-- Mobile Filter bar -->
							<div class="mobile-filter-bar col-lg-12">
								<ul class="list-unstyled list-inline no-margin no-padding">
									<li class="filter-toggle">
										<a class="">
											<i class="icon-th-list"></i>
											Filters
										</a>
									</li>
									<li>
										<div class="dropdown">
											<a data-toggle="dropdown" class="dropdown-toggle"><i class="caret "></i>{{ t('Sort by') }}</a>
											<ul class="dropdown-menu">
												<li><a href="{!! qsurl($fullUrlNoParams, Request::except(['orderBy', 'distance'])) !!}" rel="nofollow">{{ t('Sort by') }}</a></li>
												<li><a href="{!! qsurl($fullUrlNoParams, array_merge(Request::except('orderBy'), ['orderBy'=>'relevance'])) !!}" rel="nofollow">{{ t('Relevance') }}</a></li>
												<li><a href="{!! qsurl($fullUrlNoParams, array_merge(Request::except('orderBy'), ['orderBy'=>'date'])) !!}" rel="nofollow">{{ t('Date') }}</a></li>
												@if (isset($isCitySearch) and $isCitySearch)
													<li><a href="{!! qsurl($fullUrlNoParams, array_merge(Request::except('distance'), ['distance'=>100])) !!}" rel="nofollow">{{ t('Around') . ' 100 km' }}</a></li>
													<li><a href="{!! qsurl($fullUrlNoParams, array_merge(Request::except('distance'), ['distance'=>300])) !!}" rel="nofollow">{{ t('Around') . ' 300 km' }}</a></li>
													<li><a href="{!! qsurl($fullUrlNoParams, array_merge(Request::except('distance'), ['distance'=>500])) !!}" rel="nofollow">{{ t('Around') . ' 500 km' }}</a></li>
												@endif
											</ul>

										</div>
									</li>
								</ul>
							</div>
							<div class="menu-overly-mask"></div>
							<!-- Mobile Filter bar End-->


							<div class="tab-filter hide-xs" style="padding-top: 6px; padding-right: 6px;">
								<select id="orderBy" class="selecter" data-style="btn-select" data-width="auto">
									<option value="{!! qsurl($fullUrlNoParams, Request::except(['orderBy', 'distance'])) !!}">{{ t('Sort by') }}</option>
									<option{{ (Request::get('orderBy')=='relevance') ? ' selected="selected"' : '' }} value="{!! qsurl($fullUrlNoParams, array_merge(Request::except('orderBy'), ['orderBy'=>'relevance'])) !!}">{{ t('Relevance') }}</option>
									<option{{ (Request::get('orderBy')=='date') ? ' selected="selected"' : '' }} value="{!! qsurl($fullUrlNoParams, array_merge(Request::except('orderBy'), ['orderBy'=>'date'])) !!}">{{ t('Date') }}</option>
									@if (isset($isCitySearch) and $isCitySearch)
										<option{{ (Request::get('distance')==100) ? ' selected="selected"' : '' }} value="{!! qsurl($fullUrlNoParams, array_merge(Request::except('distance'), ['distance'=>100])) !!}">{{ t('Around :distance :unit', ['distance' => 100, 'unit' => unitOfLength()]) }}</option>
										<option{{ (Request::get('distance')==300) ? ' selected="selected"' : '' }} value="{!! qsurl($fullUrlNoParams, array_merge(Request::except('distance'), ['distance'=>300])) !!}">{{ t('Around :distance :unit', ['distance' => 300, 'unit' => unitOfLength()]) }}</option>
										<option{{ (Request::get('distance')==500) ? ' selected="selected"' : '' }} value="{!! qsurl($fullUrlNoParams, array_merge(Request::except('distance'), ['distance'=>500])) !!}">{{ t('Around :distance :unit', ['distance' => 500, 'unit' => unitOfLength()]) }}</option>
									@endif
								</select>
							</div>
							<!--/.tab-filter-->

						</div>
						<!--/.tab-box-->

						<div class="listing-filter hidden-xs">
							<div class="pull-left col-sm-10 col-xs-12">
								<div class="breadcrumb-list text-center-xs">
									{!! (isset($htmlTitle)) ? $htmlTitle : '' !!}
								</div>
							</div>
							<div class="pull-right col-sm-2 col-xs-12 text-right text-center-xs listing-view-action">
								@if (!empty(\Illuminate\Support\Facades\Input::all()))
									<a class="clear-all-button text-muted" href="{!! lurl(trans('routes.v-search', ['countryCode' => $country->get('icode')])) !!}">{{ t('Clear all') }}</a>
								@endif
							</div>
							<div style="clear:both;"></div>
						</div>
						<!--/.listing-filter-->

						<div class="adds-wrapper jobs-list">
							@include('search.inc.posts')
						</div>
						<!--/.adds-wrapper-->

						<div class="tab-box save-search-bar text-center">
							@if (Request::filled('q') and Request::get('q') != '' and $count->get('all') > 0)
								<a name="{!! qsurl($fullUrlNoParams, Request::except(['_token', 'location'])) !!}" id="saveSearch" count="{{ $count->get('all') }}">
									<i class=" icon-star-empty"></i> {{ t('Save Search') }}
								</a>
							@else
								<a href="#"> &nbsp; </a>
							@endif
						</div>
					</div>

					<div class="pagination-bar text-center">
						{!! $posts->appends(Request::except('page'))->render() !!}
					</div>
					<!--/.pagination-bar -->

					@if (!\Auth::check())
						<div class="post-promo text-center">
							<h2> {{ t('Looking for a job?') }} </h2>
							<h5> {{ t('Upload your CV and easily apply to jobs from any device!') }} </h5>
							<a href="{{ lurl(trans('routes.register')) . '?type=3' }}" class="btn btn-lg btn-border btn-post btn-danger">
								{{ t('Upload your CV') }}
							</a>
						</div>
						<!--/.post-promo-->
					@endif

				</div>

				<div style="clear:both;"></div>

				<!-- Advertising -->
				@include('layouts.inc.advertising.bottom')

			</div>

		</div>
	</div>
@endsection

@section('modal_location')
	@parent
	@include('layouts.inc.modal.location')
@endsection

@section('after_scripts')
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
			$('#postType a').click(function (e) {
				e.preventDefault();
				var goToUrl = $(this).attr('href');
				redirect(goToUrl);
			});
			$('#orderBy').change(function () {
				var goToUrl = $(this).val();
				redirect(goToUrl);
			});
		});
	</script>
@endsection
