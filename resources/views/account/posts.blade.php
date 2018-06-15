@extends('layouts.master')

@section('content')
	<div class="main-container inside-account">
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
						@if ($pagePath=='my-posts')
							<h2 class="title-2"><i class="icon-docs"></i> {{ t('My Ads') }} </h2>
						@elseif ($pagePath=='archived')
							<h2 class="title-2"><i class="icon-folder-close"></i> {{ t('Archived ads') }} </h2>
						@elseif ($pagePath=='favorite')
							<h2 class="title-2"><i class="icon-heart-1"></i> {{ t('Favorite jobs') }} </h2>
						@elseif ($pagePath=='pending-approval')
							<h2 class="title-2"><i class="icon-hourglass"></i> {{ t('Pending approval') }} </h2>
						@else
							<h2 class="title-2"><i class="icon-docs"></i> {{ t('Posts') }} </h2>
						@endif

						<div class="table-responsive">
							<form name="listForm" method="POST" action="{{ lurl('account/'.$pagePath.'/delete') }}">
								{!! csrf_field() !!}
								<div class="table-action">
									<label for="checkAll">
										<input type="checkbox" id="checkAll">
										{{ t('Select') }}: {{ t('All') }} |
										<button type="submit" class="btn btn-xs btn-danger delete-action">
                                            {{ t('Delete') }} <i class="glyphicon glyphicon-remove"></i>
                                        </button>
									</label>
									<div class="table-search pull-right col-xs-7">
										<div class="form-group">
											<label class="col-xs-5 control-label text-right">{{ t('Search') }} <br>
												<a title="clear filter" class="clear-filter" href="#clear">[{{ t('clear') }}]</a> </label>
											<div class="col-xs-7 searchpan">
												<input type="text" class="form-control" id="filter">
											</div>
										</div>
									</div>
								</div>
								<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo"
									   data-filter="#filter" data-filter-text-only="true">
									<thead>
									<tr>
										<th data-type="numeric" data-sort-initial="true"></th>
										<th> {{ t('Photo') }}</th>
										<th data-sort-ignore="true"> {{ t('Adds Details') }} </th>
										<th data-type="numeric"> --</th>
										<th> {{ t('Option') }}</th>
									</tr>
									</thead>
									<tbody>

									<?php
                                    if (isset($posts) && $posts->count() > 0):
									foreach($posts as $key => $post):
										// Fixed 1
										if ($pagePath == 'favorite') {
											if (isset($post->post)) {
												if (!empty($post->post)) {
													$post = $post->post;
												} else {
													continue;
												}
											} else {
												continue;
											}
										}

										// Fixed 2
										if (!$countries->has($post->country_code)) continue;

										// Get Post's URL
										$postUrl = lurl(slugify($post->title) . '/' . $post->id . '.html');

										// Get Post's City
										if ($post->city) {
											$city = $post->city->name;
										} else {
											$city = '-';
										}

                                        // Get Payment Info
                                        $payment = \App\Models\Payment::where('post_id', $post->id)->orderBy('id', 'DESC')->first();

                                        // Get Package Info
                                        $package = null;
                                        if (!empty($payment)) {
                                            $package = \App\Models\Package::transById($payment->package_id);
                                        }

										// Get country flag
										$iconPath = 'images/flags/16/' . strtolower($post->country_code) . '.png';
									?>
									<tr>
										<td style="width:2%" class="add-img-selector">
											<div class="checkbox">
												<label><input type="checkbox" name="post[]" value="{{ $post->id }}"></label>
											</div>
										</td>
										<td style="width:14%" class="add-img-td">
											<a href="{{ $postUrl }}">
												<img class="thumbnail img-responsive" src="{{ resize(\App\Models\Post::getLogo($post->logo), 'medium') }}" alt="img">
											</a>
										</td>
										<td style="width:58%" class="ads-details-td">
											<div>
												<p>
                                                    <strong>
                                                        <a href="{{ $postUrl }}" title="{{ $post->title }}">{{ str_limit($post->title, 40) }}</a>
                                                    </strong>
                                                    @if (isset($package) and !empty($package))
                                                        <?php
                                                        if ($post->featured == 1) {
                                                            $color = $package->ribbon;
                                                            $packageInfo = '';
                                                        } else {
                                                            $color = '#ddd';
                                                            $packageInfo = ' (' . t('Expired') . ')';
                                                        }
                                                        ?>
                                                        <i class="fa fa-check-circle tooltipHere" style="color: {{ $color }};" title="" data-placement="bottom"
                                                           data-toggle="tooltip" data-original-title="{{ $package->short_name . $packageInfo }}"></i>
                                                    @endif
                                                </p>
												<p>
													<strong><i class="icon-clock" title="{{ t('Posted On') }}"></i></strong> {{ $post->created_at->formatLocalized('%d %B %Y %H:%M') }}
												</p>
												<p>
													<strong><i class="icon-eye" title="{{ t('Visitors') }}"></i></strong> {{ $post->visits or 0 }}
													<strong><i class="fa fa-map-marker" title="{{ t('Located In') }}"></i></strong> {{ $city }}
													@if (file_exists(public_path($iconPath)))
														<img src="{{ url($iconPath) }}" data-toggle="tooltip" title="{{ $post->country_code }}">
													@endif
												</p>
											</div>
										</td>
										<td style="width:16%" class="price-td">
											<div>
												<strong>
													{!! \App\Helpers\Number::money($post->salary_min) !!}
												</strong>
											</div>
										</td>
										<td style="width:10%" class="action-td">
											<div>
												@if ($post->user_id==$user->id and $post->archived==0)
													<p>
                                                        <a class="btn btn-primary btn-xs" href="{{ lurl('posts/' . $post->id . '/edit') }}">
                                                            <i class="fa fa-edit"></i> {{ t('Edit') }}
                                                        </a>
                                                    </p>
												@endif
												@if (isVerifiedPost($post) and $post->archived==0)
													<!--<p>
														<a class="btn btn-info btn-xs"> <i class="fa fa-mail-forward"></i> {{ t('Share') }} </a>
													</p>-->
												@endif
												@if ($post->user_id==$user->id and $post->archived==1)
													<p>
                                                        <a class="btn btn-info btn-xs" href="{{ lurl('account/'.$pagePath.'/'.$post->id.'/repost') }}">
                                                            <i class="fa fa-recycle"></i> {{ t('Repost') }}
                                                        </a>
                                                    </p>
												@endif
												<p>
                                                    <a class="btn btn-danger btn-xs delete-action" href="{{ lurl('account/'.$pagePath.'/'.$post->id.'/delete') }}">
                                                        <i class="fa fa-trash"></i> {{ t('Delete') }}
                                                    </a>
                                                </p>
											</div>
										</td>
									</tr>
									<?php endforeach; ?>
                                    <?php endif; ?>
									</tbody>
								</table>
							</form>
						</div>

                        <div class="pagination-bar text-center">
                            {{ (isset($posts)) ? $posts->links() : '' }}
                        </div>

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
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

			$('#checkAll').click(function () {
				checkAll(this);
			});

			$('a.delete-action, button.delete-action').click(function(e)
			{
				e.preventDefault(); /* prevents the submit or reload */
				var confirmation = confirm("{{ t('Are you sure you want to perform this action?') }}");

				if (confirmation) {
					if( $(this).is('a') ){
						var url = $(this).attr('href');
						if (url !== 'undefined') {
							redirect(url);
						}
					} else {
						$('form[name=listForm]').submit();
					}

				}

				return false;
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
