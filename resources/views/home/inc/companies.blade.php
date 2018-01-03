@if (isset($featuredCompanies) and !empty($featuredCompanies) and !empty($featuredCompanies->posts))
<div class="col-lg-12 content-box ">
	<div class="row row-featured row-featured-category row-featured-company">
		<div class="col-lg-12  box-title no-border">
			<div class="inner">
				<h2>
					<span class="title-3">{!! $featuredCompanies->title !!}</span>
					<a class="sell-your-item" href="{{ $featuredCompanies->link }}">
						{{ t('View more') }}
						<i class="icon-th-list"></i>
					</a>
				</h2>
			</div>
		</div>

		@foreach($featuredCompanies->posts as $key => $post)
			<?php
			// Ads URL setting
			$companyUrl = lurl(trans('routes.v-search-company', ['countryCode' => $country->get('icode'), 'companyName' => $post->company_name]));
			?>
			<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4 f-category">
				<a href="{{ $companyUrl }}">
					<img alt="{{ mb_ucfirst($post->company_name) }}" class="img-responsive" src="{{ resize(\App\Models\Post::getLogo($post->logo), 'medium') }}">
					<h6> {{ t('Jobs at') }}
						<span class="company-name">{{ mb_ucfirst($post->company_name) }}</span>
						<span class="jobs-count text-muted">({{ mb_ucfirst($post->count_posts) }})</span>
					</h6>
				</a>
			</div>
		@endforeach

	</div>
</div>

<div style="clear: both"></div>
@endif
