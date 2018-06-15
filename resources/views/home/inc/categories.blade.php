<div class="col-lg-12 content-box">
	<div class="row row-featured row-featured-category">
		<div class="col-lg-12 box-title no-border">
			<div class="inner">
				<h2>
					<span class="title-3">{{ t('Browse by') }} <span style="font-weight: bold;">{{ t('Category') }}</span></span>
					<a href="{{ lurl(trans('routes.v-sitemap', ['countryCode' => $country->get('icode')])) }}"
					   class="sell-your-item">
						{{ t('View more') }} <i class="icon-th-list"></i>
					</a>
				</h2>
			</div>
		</div>

		@if (isset($categories) and $categories->count() > 0)
		<div style="padding: 0 10px 0 20px;">
			@foreach ($categories as $key => $items)
				<ul class="cat-list list list-check col-xs-4 {{ (count($categories) == $key+1) ? 'cat-list-border' : '' }}" style="padding: 25px;">
					@foreach ($items as $k => $cat)
						<li>
							<a href="{{ lurl(trans('routes.v-search-cat', ['countryCode' => $country->get('icode'), 'catSlug' => $cat->slug])) }}">
								{{ $cat->name }}
							</a>
						</li>
					@endforeach
				</ul>
			@endforeach
		</div>
		@endif

	</div>
</div>

<div style="clear: both"></div>
