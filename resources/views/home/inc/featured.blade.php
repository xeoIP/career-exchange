<?php
if (!isset($cacheExpiration)) {
    $cacheExpiration = (int)config('settings.app_cache_expiration');
}
?>
@if (isset($featured) and !empty($featured) and !empty($featured->posts))
<div class="col-lg-12 content-box">
	<div class="row row-featured">
		<div class="col-lg-12 box-title">
			<div class="inner">
				<h2>
					<span class="title-3">{!! $featured->title !!}</span>
					<a href="{{ $featured->link }}" class="sell-your-item">
						{{ t('View more') }} <i class="icon-th-list"></i>
					</a>
				</h2>
			</div>
		</div>

		<div style="clear: both"></div>

		<div class="relative content featured-list-row clearfix">

			<div class="large-12 columns">
				<div class="no-margin featured-list-slider owl-carousel owl-theme">
					<?php
					foreach($featured->posts as $key => $post):
						if (!$countries->has($post->country_code)) continue;

						// Ads URL setting
						$postUrl = lurl(slugify($post->title) . '/' . $post->id . '.html');

						// Get the Post's Type
						$cacheId = 'postType.' . $post->post_type_id . '.' . config('app.locale');
						$postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                            $postType = \App\Models\PostType::transById($post->post_type_id);
							return $postType;
						});
						if (empty($postType)) continue;
					?>
						<div class="item">
							<a href="{{ $postUrl }}">
								<span class="item-carousel-thumb">
									<img class="img-responsive" src="{{ resize(\App\Models\Post::getLogo($post->logo), 'medium') }}" alt="{{ mb_ucfirst($post->title) }}" style="border: 1px solid #e7e7e7; margin-top: 2px;">
								</span>
								<span class="item-name">{{ mb_ucfirst(str_limit($post->title, 70)) }}</span>
								<span class="price">
									{{ $postType->name }}
								</span>
							</a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>
</div>
@endif

@section('before_scripts')
	@parent
	<script>
		/* Carousel Parameters */
		var carouselItems = {{ (isset($featured) and isset($featured->posts)) ? collect($featured->posts)->count() : 0 }};
		var carouselAutoplay = {{ (isset($featuredOptions) && isset($featuredOptions['autoplay'])) ? $featuredOptions['autoplay'] : 'false' }};
		var carouselAutoplayTimeout = {{ (isset($featuredOptions) && isset($featuredOptions['autoplay_timeout'])) ? $featuredOptions['autoplay_timeout'] : 1500 }};
	</script>
@endsection
