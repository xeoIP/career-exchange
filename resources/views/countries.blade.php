<?php
use App\Helpers\Localization\Country as CountryLocalization;
?>
@extends('layouts.master')

@section('header')
	@if (Auth::check())
		@include('layouts.inc.header', ['country' => $country, 'user' => $user])
	@else
		@include('layouts.inc.header', ['country' => $country])
	@endif
@endsection

@section('search')
	@parent
@endsection

@section('content')
	<div class="main-container inner-page">
		<div class="container">
			<div class="section-content">
				<div class="row">

					<h1 class="text-center title-1" style="text-transform: none;">
						<strong>{{ t('Our websites abroad') }}</strong>
					</h1>
					<hr class="center-block small text-hr">

					@if (isset($countryCols))
						<div class="col-md-12 page-content">
							<div class="inner-box relative">
								<div class="row">
									<div>
										<h3 class="title-2"><i class="icon-location-2"></i> {{ t('Select a country') }} </h3>
										<div class="row" style="padding: 0 20px">
											@foreach ($countryCols as $key => $col)
												<ul class="cat-list col-xs-3 {{ (count($countryCols) == $key+1) ? 'cat-list-border' : '' }}">
													@foreach ($col as $k => $country)
														<?php
                                                        $countryLang = CountryLocalization::getLangFromCountry($country->get('languages'));
														?>
														<li>
															<img src="{{ url('images/blank.gif') . getPictureVersion() }}" class="flag flag-{{ ($country->get('icode')=='uk') ? 'gb' : $country->get('icode') }}" style="margin-bottom: 4px; margin-right: 5px;">
															<a href="{{ url('/' . $countryLang->get('abbr') . '/?d=' . $country->get('code')) }}">
																{{ str_limit($country->get('name'), 100) }}
															</a>
														</li>
													@endforeach
												</ul>
											@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>
					@endif

				</div>

				@include('layouts.inc.social.horizontal')

			</div>
		</div>
	</div>
	<!-- /.main-container -->
@endsection

@section('info')
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			$('#countries').change(function () {
				var goToUrl = $(this).val();
				redirect(goToUrl);
			});
		});
	</script>
@endsection
