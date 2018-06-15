<div class="container">
	<div class="intro">
		<div class="dtable hw100">
			<div class="dtable-cell hw100">
				<div class="container text-center">
					<div class="row search-row">
						<form id="seach" name="search" action="{{ lurl(trans('routes.v-search', ['countryCode' => $country->get('icode')])) }}" method="GET">
							<div class="col-lg-5 col-sm-5 search-col relative">
								<i class="icon-docs icon-append"></i>
								<input type="text" name="q" class="form-control keyword has-icon" placeholder="{{ t('What?') }}" value="">
							</div>
							<div class="col-lg-5 col-sm-5 search-col relative locationicon">
								<i class="icon-location-2 icon-append"></i>
								<input type="hidden" id="lSearch" name="l" value="">
								<input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
									   placeholder="{{ t('Where?') }}" value="" title="" data-placement="bottom"
									   data-toggle="tooltip" type="button"
									   data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}">
							</div>
							<div class="col-lg-2 col-sm-2 search-col">
								<button class="btn btn-primary btn-search btn-block"><i class="icon-search"></i> <strong>{{ t('Search') }}</strong>
								</button>
							</div>
							{!! csrf_field() !!}
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
