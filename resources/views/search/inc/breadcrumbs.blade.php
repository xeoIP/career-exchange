<div class="container">
	<div class="breadcrumbs">
		<ol class="breadcrumb pull-left">
			<li><a href="{{ lurl('/') }}"><i class="icon-home fa"></i></a></li>
			<li>
				<a href="{{ lurl(trans('routes.v-search', ['countryCode' => $country->get('icode')])) }}">
					{{ $country->get('name') }}
				</a>
			</li>
			@if (isset($bcTab) and count($bcTab) > 0)
				@foreach($bcTab as $key => $value)
                    <?php $value = collect($value); ?>
					@if ($value->get('position') > count($bcTab)+1)
						<li class="active">
							{!! $value->get('name') !!}
							&nbsp;
							@if (isset($city) or isset($admin))
								<a href="#browseAdminCities" id="dropdownMenu1" data-toggle="modal"> <span class="caret"></span> </a>
							@endif
						</li>
					@else
						<li><a href="{{ $value->get('url') }}">{!! $value->get('name') !!}</a></li>
					@endif
				@endforeach
			@endif
		</ol>
	</div>
</div>
