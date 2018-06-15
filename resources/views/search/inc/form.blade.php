<?php
use Illuminate\Support\Facades\Input;

// Keywords
$keywords = rawurldecode(Input::get('q'));

// Category
$qCategory = (isset($cat) and !empty($cat)) ? $cat->tid : Input::get('c');

// Location
if (isset($city) and !empty($city)) {
	$qLocationId = (isset($city->id)) ? $city->id : 0;
	$qLocation = $city->name;
    $qAdmin = Input::get('r');
} else {
	$qLocationId = Input::get('l');
    $qLocation = (Input::filled('r')) ? t('area:') . rawurldecode(Input::get('r')) : Input::get('location');
    $qAdmin = Input::get('r');
}
?>
<div class="container">
	<div class="search-row-wrapper">
		<div class="container">
			<form id="seach" name="search" action="{{ lurl(trans('routes.v-search', ['countryCode' => $country->get('icode')])) }}" method="GET">
				<div class="col-sm-4" style="padding-left: 1px; padding-right: 1px;">
					<input name="q" class="form-control keyword" type="text" placeholder="{{ t('What?') }}" value="{{ $keywords }}">
				</div>
				<div class="col-sm-3" style="padding-left: 1px; padding-right: 1px;">
					<select name="c" id="catSearch" class="form-control selecter">
						<option value="" {{ ($qCategory=='') ? 'selected="selected"' : '' }}> {{ t('All Categories') }} </option>
						@if (isset($cats) and $cats->count() > 0)
							@foreach ($cats->groupBy('parent_id')->get(0) as $itemCat)
								<option{{ ($qCategory==$itemCat->tid) ? ' selected="selected"' : '' }} value="{{ $itemCat->tid }}"> {{ $itemCat->name }} </option>
							@endforeach
						@endif
					</select>
				</div>
				<div class="col-sm-3 search-col locationicon" style="padding-left: 1px; padding-right: 1px;">
					<i class="icon-location-2 icon-append" style="margin-top: -6px; margin-left: 1px;"></i>
					<input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon tooltipHere"
						   placeholder="{{ t('Where?') }}" value="{{ $qLocation }}" title="" data-placement="bottom"
						   data-toggle="tooltip" type="button"
						   data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}">
				</div>

				<input type="hidden" id="lSearch" name="l" value="{{ $qLocationId }}">
				<input type="hidden" id="rSearch" name="r" value="{{ $qAdmin }}">

				<div class="col-sm-2" style="padding-left: 1px; padding-right: 1px;">
					<button class="btn btn-block btn-primary"><i class="fa fa-search"></i></button>
				</div>
				{!! csrf_field() !!}
			</form>
		</div>
	</div>
	<!-- /.search-row  width: 24.6%;-->
</div>