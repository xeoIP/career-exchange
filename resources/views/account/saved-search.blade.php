@extends('layouts.master')

@section('content')
	<div class="main-container">
		<div class="container">
			<div class="row">

				@if (Session::has('flash_notification'))
					<div class="container" style="margin-bottom: -10px; margin-top: -10px;">
						<div class="row">
							<div class="col-lg-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-sm-3 page-sidebar">
					@include('account/inc/sidebar')
				</div>
				<!--/.page-sidebar-->

				<div class="col-sm-9 page-content">
					<div class="inner-box">
						<h2 class="title-2"><i class="icon-star-circled"></i> {{ t('Saved search') }} </h2>
						<div class="row">

							<div class="col-sm-4">
								<ul class="list-group list-group-unstyle">
									@if (isset($savedSearch) and $savedSearch->getCollection()->count() > 0)
										@foreach ($savedSearch->getCollection() as $search)
											<li class="list-group-item {{ (Request::get('q')==$search->keyword) ? 'active' : '' }}">
												<a href="{{ lurl('account/saved-search/?'.$search->query.'&pag='.Request::get('pag')) }}" class="">
													<span> {{ str_limit(strtoupper($search->keyword), 20) }} </span>
													<span class="label label-warning" id="{{ $search->id }}">{{ $search->count }}+</span>
												</a>
												<span class="delete-search-result">
                                                    <a href="{{ lurl('account/saved-search/'.$search->id.'/delete') }}">&times;</a>
                                                </span>
											</li>
										@endforeach
									@else
										<div>
                                            {{ t('You have no save search.') }}
										</div>
									@endif
								</ul>
								<div class="pagination-bar text-center">
									{{ (isset($savedSearch)) ? $savedSearch->links() : '' }}
								</div>
							</div>

							<div class="col-sm-8">
								<div class="adds-wrapper">

                                    @if (isset($savedSearch) and $savedSearch->getCollection()->count() > 0)
                                        @if (isset($posts) and $posts->getCollection()->count() > 0)
                                            <?php
                                            foreach($posts->getCollection() as $key => $post):
                                            if (isset($countries) and !$countries->has($post->country_code)) continue;

                                            // Get PostType Info
                                            $postType = \App\Models\PostType::transById($post->post_type_id);
                                            if (empty($postType)) continue;

                                            // Post URL setting
                                            $postUrl = lurl(slugify($post->title) . '/' . $post->id . '.html');
                                            ?>
                                            <div class="item-list">
                                                <div class="col-sm-2 no-padding photobox">
                                                    <div class="add-image">
                                                        <span class="photo-count"><i class="fa fa-camera"></i> </span>
                                                        <a href="{{ $postUrl }}">
                                                            <img class="thumbnail no-margin" src="{{ resize(\App\Models\Post::getLogo($post->logo), 'medium') }}" alt="img">
                                                        </a>
                                                    </div>
                                                </div>
                                                <!--/.photobox-->
                                                <div class="col-sm-8 add-desc-box">
                                                    <div class="ads-details">
                                                        <h5 class="add-title"><a href="{{ $postUrl }}"> {{ $post->title }} </a></h5>
														<span class="info-row">
															<span class="add-type business-ads tooltipHere" data-toggle="tooltip" data-placement="right" title="{{ $postType->name }}">
																{{ strtoupper(mb_substr($postType->name, 0, 1)) }}
															</span>
															<?php
															// Convert the created_at date to Carbon object
															$post->created_at = \Date::parse($post->created_at)->timezone(config('timezone.id'));
															$post->created_at = $post->created_at->ago();

                                                            $adCat = \App\Models\Category::transById($post->category_id);
                                                            $adCity = \App\Models\City::find($post->city_id);
															?>
															<span class="date"><i class=" icon-clock"> </i> {{ $post->created_at }} </span>
															@if (!empty($adCat))
																- <span class="category">{{ $adCat->name }} </span>
															@endif
															@if (!empty($adCity))
																- <span class="item-location"><i class="fa fa-map-marker"></i> {{ $adCity->name }}</span>
															@endif
														</span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-2 text-right text-center-xs price-box">
                                                    <h4 class="item-price">
                                                        {!! \App\Helpers\Number::money($post->salary_max) !!}
                                                    </h4>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        @else
                                            <div class="text-center">
                                                {{ t('Please select a saved search to show the result') }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center">
                                            {{-- t('No result. Refine your search using other criteria.') --}}
                                        </div>
                                    @endif
								</div>
                                <div class="pagination-bar text-center">
                                    {{ (isset($posts)) ? $posts->appends(Request::except(['page']))->links() : '' }}
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<!-- include footable   -->
	<script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
	<script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
	<script type="text/javascript">
		$(function () {
			$('#addManageTable').footable().bind('footable_filtering', function (e) {
				var selected = $('.filter-status').find(':selected').text();
				if (selected && selected.length > 0) {
					e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
					e.clear = !e.filter;
				}
			});

			$('.clear-filter').click(function (e) {
				e.preventDefault();
				$('.filter-status').val('');
				$('table.demo').trigger('footable_clear_filter');
			});

		});
	</script>
	<!-- include custom script for ads table [select all checkbox]  -->
	<script>

		function checkAll(bx) {
			var chkinput = document.getElementsByTagName('input');
			for (var i = 0; i < chkinput.length; i++) {
				if (chkinput[i].type == 'checkbox') {
					chkinput[i].checked = bx.checked;
				}
			}
		}

	</script>
@endsection
