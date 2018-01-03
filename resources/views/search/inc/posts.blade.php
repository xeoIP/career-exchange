<?php
if (!isset($cacheExpiration)) {
    $cacheExpiration = (int)config('settings.app_cache_expiration');
}
?>
@if (isset($posts) and $posts->getCollection()->count() > 0)
	<?php
    if (!isset($cats)) {
        $cats = collect([]);
    }

	foreach($posts->getCollection() as $key => $post):
		if (!$countries->has($post->country_code)) continue;

		// Get Post's URL
		$postUrl = lurl(slugify($post->title) . '/' . $post->id . '.html');
		?>
		<div class="item-list job-item">
			<div class="col-sm-1 col-xs-2 no-padding photobox">
				<div class="add-image">
					<a href="{{ $postUrl }}">
						<img class="thumbnail no-margin" src="{{ resize(\App\Models\Post::getLogo($post->logo), 'medium') }}" alt="{{ mb_ucfirst($post->company_name) }}">
					</a>
				</div>
			</div>
			<!--/.photobox-->
			<div class="col-sm-10 col-xs-10 add-desc-box">
				<div class="add-details jobs-item">
					<h5 class="company-title ">
						<a href="{{ lurl(trans('routes.v-search-company', ['countryCode' => $country->get('icode'), 'companyName' => $post->company_name])) }}">
							{{ mb_ucfirst($post->company_name) }}
						</a>
					</h5>
					<h4 class="job-title">
						<a href="{{ $postUrl }}"> {{ mb_ucfirst(str_limit($post->title, 70)) }} </a>
					</h4>
					<?php
					// Convert the created_at date to Carbon object
					$post->created_at = \Date::parse($post->created_at)->timezone(config('timezone.id'));
					$post->created_at = $post->created_at->ago();

					// Category
                    $cacheId = 'category.' . $post->category_id . '.' . config('app.locale');
                    $liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                        $liveCat = \App\Models\Category::find($post->category_id);
                        return $liveCat;
                    });

					// Check parent
					if (empty($liveCat->parent_id)) {
						$liveCatParentId = $liveCat->id;
					} else {
						$liveCatParentId = $liveCat->parent_id;
					}

					// Check translation
					if ($cats->has($liveCatParentId)) {
						$liveCatName = $cats->get($liveCatParentId)->name;
					} else {
						$liveCatName = $liveCat->name;
					}

                    // Get the Post's Type
                    $cacheId = 'postType.' . $post->post_type_id . '.' . config('app.locale');
                    $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                        $postType = \App\Models\PostType::transById($post->post_type_id);
                        return $postType;
                    });
                    if (empty($postType)) continue;

                    // Get the Post's Salary Type
                    $cacheId = 'salaryType.' . $post->salary_type_id . '.' . config('app.locale');
                    $salaryType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                        $salaryType = \App\Models\SalaryType::transById($post->salary_type_id);
                        return $salaryType;
                    });
                    if (empty($salaryType)) continue;

                    // Get the Post's City
                    $cacheId = config('country.code') . '.city.' . $post->city_id;
                    $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                        $city = \App\Models\City::find($post->city_id);
                        return $city;
                    });
                    if (empty($city)) continue;
					?>
					<span class="info-row">
						<span class="date">
							<i class="icon-clock"> </i>
							{{ $post->created_at }}
						</span>
						<span class="item-location">
							<i class="fa fa-map-marker"></i>
							<a href="{!! qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => $country->get('icode')]), array_merge(Request::except(['l', 'location']), ['l'=>$post->city_id])) !!}">
								{{ $city->name }}
							</a>
							{{ (isset($post->distance)) ? '- ' . round(lengthPrecision($post->distance), 2) . unitOfLength() : '' }}
						</span>
						<span class="post_type">
							<i class="icon-tag"> </i>
							{{ $postType->name }}
						</span>
						<span class="salary">
							<i class="icon-money"> </i>
							@if ($post->salary_max > 0)
								{!! \App\Helpers\Number::money($post->salary_min) !!} -
								{!! \App\Helpers\Number::money($post->salary_max) !!}
							@else
								{!! \App\Helpers\Number::money('--') !!}
							@endif
							{{ t('a') }} {{ $salaryType->name }}
						</span>
					</span>

					<div class="jobs-desc">
						{!! str_limit(str_clean($post->description), 180) !!}
					</div>

					<div class="job-actions">
						<ul class="list-unstyled list-inline">
							@if (Auth::check())
								@if (\App\Models\SavedPost::where('user_id', $user->id)->where('post_id', $post->id)->count() <= 0)
								<li id="{{ $post->id }}">
									<a class="save-job" id="save-{{ $post->id }}">
										<span class="fa fa-heart-o"></span>
										{{ t('Save Job') }}
									</a>
								</li>
								@else
								<li class="saved-job" id="{{ $post->id }}">
									<a class="saved-job" id="saved-{{ $post->id }}">
										<span class="fa fa-heart"></span>
										{{ t('Saved Job') }}
									</a>
								</li>
								@endif
							@else
								<li id="{{ $post->id }}">
									<a class="save-job" id="save-{{ $post->id }}">
										<span class="fa fa-heart-o"></span>
										{{ t('Save Job') }}
									</a>
								</li>
							@endif
							<li>
								<a class="email-job" data-toggle="modal" data-id="{{ $post->id }}" href="#sendByEmail" id="email-{{ $post->id }}">
									<i class="fa fa-envelope"></i>
									{{ t('Email Job') }}
								</a>
							</li>
						</ul>
					</div>

				</div>
			</div>
			<!--/.add-desc-box-->

			<!--/.add-desc-box-->
		</div>
		<!--/.job-item-->
	<?php endforeach; ?>
@else
	<div class="item-list">
		{{ t('No result. Refine your search using other criteria.') }}
	</div>
@endif

@section('modal_location')
	@parent
	@include('layouts.inc.modal.send-by-email')
@endsection

@section('after_scripts')
	@parent
	<script>
		$(document).ready(function ()
		{
			/* Get Post ID */
			$('.email-job').click(function(){
				var postId = $(this).attr("data-id");
				$('input[type=hidden][name=post]').val(postId);
			});

			@if (count($errors) > 0)
				@if (old('sendByEmailForm')=='1')
					$('#sendByEmail').modal();
				@endif
			@endif
		})
	</script>
@endsection
