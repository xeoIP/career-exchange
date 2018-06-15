<?php
if (!isset($cacheExpiration)) {
    $cacheExpiration = (int)config('settings.app_cache_expiration');
}
?>
@if (isset($latest) and !empty($latest) and !empty($latest->posts))
    <div class="row">

        <div class="col-sm-12 page-content col-thin-right">
            <div class="content-box col-lg-12">
                <div class="row row-featured row-featured-category">
                    <div class="col-lg-12 box-title no-border">
                        <div class="inner">
                            <h2>
                                <span class="title-3">{!! $latest->title !!}</span>
                                <a href="{{ $latest->link }}" class="sell-your-item">
                                    {{ t('View more') }} <i class="icon-th-list"></i>
                                </a>
                            </h2>
                        </div>
                    </div>

                    <div class="adds-wrapper jobs-list">

                        @foreach($latest->posts as $key => $post)
                            <?php
                            // Get Post's URL
                            $postUrl = lurl(slugify($post->title) . '/' . $post->id . '.html');

                            // Get the Post's City
                            $cacheId = config('country.code') . '.city.' . $post->city_id;
                            $city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                                $city = \App\Models\City::find($post->city_id);
                                return $city;
                            });

                            // Get the Post's Type
                            $cacheId = 'postType.' . $post->post_type_id . '.' . config('app.locale');
                            $postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                                $postType = \App\Models\PostType::transById($post->post_type_id);
                                return $postType;
                            });

                            // Get the Post's Salary Type
                            $cacheId = 'salaryType.' . $post->salary_type_id . '.' . config('app.locale');
                            $salaryType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
                                $salaryType = \App\Models\SalaryType::transById($post->salary_type_id);
                                return $salaryType;
                            });
                            ?>
                            @continue(empty($city))
                            @continue(empty($postType))
                            @continue(empty($salaryType))
                        <div class="item-list job-item">
                            <div class="col-sm-1 col-xs-2 no-padding photobox">
                                <div class="add-image">
                                    <a href="{{ $postUrl }}">
                                        <img alt="{{ mb_ucfirst($post->company_name) }}" src="{{ resize(\App\Models\Post::getLogo($post->logo), 'medium') }}" class="thumbnail no-margin">
                                    </a>
                                </div>
                            </div>
                            <!--/.photobox-->
                            <div class="col-sm-10 col-xs-10  add-desc-box">
                                <div class="add-details jobs-item">
                                    <h5 class="company-title ">
                                        <a href="{{ lurl(trans('routes.v-search-company', ['countryCode' => $country->get('icode'), 'companyName' => $post->company_name])) }}">
                                            {{ mb_ucfirst($post->company_name) }}
                                        </a>
                                    </h5>
                                    <h4 class="job-title">
                                        <a href="{{ $postUrl }}">
                                            {{ mb_ucfirst($post->title) }}
                                        </a>
                                    </h4>
                                    <?php
                                    // Convert the created_at date to Carbon object
                                    $post->created_at = \Date::parse($post->created_at)->timezone(config('timezone.id'));
                                    $post->created_at = $post->created_at->ago();
                                    ?>
                                    <span class="info-row">
                                        <span class="date">
                                            <i class="icon-clock"> </i>
                                            {{ $post->created_at }}
                                        </span>
                                        <span class="item-location">
                                            <i class="fa fa-map-marker"></i>
                                            {{ $city->name }}
                                        </span>
                                        <span class="date">
                                            <i class="icon-clock"> </i>
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

                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="saved-job" id="{{ $post->id }}">
                                                        <a class="saved-job" id="saved-{{ $post->id }}">
                                                            <span class="fa fa-heart"></span>

                                                        </a>
                                                    </li>
                                                @endif
                                            @else
                                                <li id="{{ $post->id }}">
                                                    <a class="save-job" id="save-{{ $post->id }}">
                                                        <span class="fa fa-heart-o"></span>

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
                        @endforeach

                    </div>

                    <div class="tab-box save-search-bar text-center">
                        <a class="text-uppercase" href="{{ lurl(trans('routes.v-search', ['countryCode' => $country->get('icode')])) }}">
                            <i class="icon-briefcase"></i>
                            {{ t('View all jobs') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endif

@section('modal_location')
    @parent
    @include('layouts.inc.modal.send-by-email')
@endsection

@section('after_scripts')
    @parent
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
		});
    </script>
@endsection
