<?php
// Default Map's values
$map = [
    'show' 				=> false,
    'backgroundColor' 	=> 'transparent',
    'border' 			=> '#26ae61',
    'hoverBorder' 		=> '#26ae61',
    'borderWidth' 		=> 4,
    'color' 			=> '#26ae6152',
    'hover' 			=> '#26ae61',
    'width' 			=> '400px',
    'height' 			=> '400px',
];

// Blue Theme values
if (config('app.skin') == 'skin-blue') {
    $map = [
        'show' 				=> false,
        'backgroundColor' 	=> 'transparent',
        'border' 			=> '#4682B4',
        'hoverBorder' 		=> '#4682B4',
        'borderWidth' 		=> 4,
        'color' 			=> '#d5e3ef',
        'hover' 			=> '#4682B4',
        'width' 			=> '300px',
        'height' 			=> '300px',
    ];
}

// Green Theme values
if (config('app.skin') == 'skin-green') {
    $map = [
        'show' 				=> false,
        'backgroundColor' 	=> 'transparent',
        'border' 			=> '#228B22',
        'hoverBorder' 		=> '#228B22',
        'borderWidth' 		=> 4,
        'color' 			=> '#cae7ca',
        'hover' 			=> '#228B22',
        'width' 			=> '300px',
        'height' 			=> '300px',
    ];
}

// Red Theme values
if (config('app.skin') == 'skin-red') {
    $map = [
        'show' 				=> false,
        'backgroundColor' 	=> 'transparent',
        'border' 			=> '#fa2320',
        'hoverBorder' 		=> '#fa2320',
        'borderWidth' 		=> 4,
        'color' 			=> '#f0d9d8',
        'hover' 			=> '#fa2320',
        'width' 			=> '300px',
        'height' 			=> '300px',
    ];
}

// Yellow Theme values
if (config('app.skin') == 'skin-yellow') {
    $map = [
        'show' 				=> false,
        'backgroundColor' 	=> 'transparent',
        'border' 			=> '#ffd005',
        'hoverBorder' 		=> '#ffd005',
        'borderWidth' 		=> 4,
        'color' 			=> '#fcf8e3',
        'hover' 			=> '#2ecc71',
        'width' 			=> '300px',
        'height' 			=> '300px',
    ];
}

// Get Admin Map's values
if (isset($citiesOptions)) {
    if (file_exists(config('larapen.core.maps.path') . strtolower($country->get('code')) . '.svg')) {
        if (isset($citiesOptions['show_map']) and $citiesOptions['show_map'] == '1') {
            $map['show'] = true;
        }
    }
    if (isset($citiesOptions['map_background_color']) and !empty($citiesOptions['map_background_color'])) {
        $map['backgroundColor'] = $citiesOptions['map_background_color'];
    }
    if (isset($citiesOptions['map_border']) and !empty($citiesOptions['map_border'])) {
        $map['border'] = $citiesOptions['map_border'];
    }
    if (isset($citiesOptions['map_hover_border']) and !empty($citiesOptions['map_hover_border'])) {
        $map['hoverBorder'] = $citiesOptions['map_hover_border'];
    }
    if (isset($citiesOptions['map_border_width']) and !empty($citiesOptions['map_border_width'])) {
        $map['borderWidth'] = $citiesOptions['map_border_width'];
    }
    if (isset($citiesOptions['map_color']) and !empty($citiesOptions['map_color'])) {
        $map['color'] = $citiesOptions['map_color'];
    }
    if (isset($citiesOptions['map_hover']) and !empty($citiesOptions['map_hover'])) {
        $map['hover'] = $citiesOptions['map_hover'];
    }
    if (isset($citiesOptions['map_width']) and !empty($citiesOptions['map_width'])) {
        $map['width'] = $citiesOptions['map_width'];
    }
    if (isset($citiesOptions['map_height']) and !empty($citiesOptions['map_height'])) {
        $map['height'] = $citiesOptions['map_height'];
    }
}
?>

@if ($map['show'])
	<div id="countryMap" class="col-sm-3 page-sidebar col-thin-left">&nbsp;</div>
@endif

@section('after_scripts')
	@parent
	<script src="{{ url('assets/plugins/twism/jquery.twism.js') }}"></script>
	<script>
		$(document).ready(function () {
			@if ($map['show'])
				$('#countryMap').twism("create",
				{
					map: "custom",
					customMap: '{{ config('larapen.core.maps.urlBase') . strtolower($country->get('code')) . '.svg' }}',
					backgroundColor: '{{ $map['backgroundColor'] }}',
					border: '{{ $map['border'] }}',
					hoverBorder: '{{ $map['hoverBorder'] }}',
					borderWidth: {{ $map['borderWidth'] }},
					color: '{{ $map['color'] }}',
					width: '{{ $map['width'] }}',
					height: '{{ $map['height'] }}',
					click: function(region) {
						if (typeof region == "undefined") {
							return false;
						}
						if (isBlankValue(region)) {
							return false;
						}
						region = rawurlencode(region);
						var searchPage = '{{ lurl(trans('routes.v-search', ['countryCode' => $country->get('icode')])) }}';
						@if (config('larapen.core.multi_countries_website'))
							searchPage = searchPage + '?d={{ $country->get('code') }}&r=' + region;
						@else
							searchPage = searchPage + '?r=' + region;
						@endif
						redirect(searchPage);
					},
					hover: function(regionId) {
						if (typeof regionId == "undefined") {
							return false;
						}
						var selectedIdObj = document.getElementById(regionId);
						if (typeof selectedIdObj == "undefined") {
							return false;
						}
						selectedIdObj.style.fill = '{{ $map['hover'] }}';
						return;
					},
					unhover: function(regionId) {
						if (typeof regionId == "undefined") {
							return false;
						}
						var selectedIdObj = document.getElementById(regionId);
						if (typeof selectedIdObj == "undefined") {
							return false;
						}
						selectedIdObj.style.fill = '{{ $map['color'] }}';
						return;
					}
				});
			@endif
		});
	</script>
@endsection
