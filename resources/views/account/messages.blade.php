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
				@include('account.inc.sidebar')
			</div>
			<!--/.page-sidebar-->

			<div class="col-sm-9 page-content">
				<div class="inner-box">
					<h2 class="title-2"><i class="icon-mail"></i> {{ t('Messages') }} </h2>

					<div style="clear:both"></div>

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
											<a title="clear filter" class="clear-filter" href="#clear">[{{ t('clear') }}]</a>
										</label>
										<div class="col-xs-7 searchpan">
											<input type="text" class="form-control" id="filter">
										</div>
									</div>
								</div>
							</div>

							<table id="addManageTable" class="table table-striped table-bordered add-manage-table table demo" data-filter="#filter" data-filter-text-only="true">
								<thead>
								<tr>
									<th style="width:2%" data-type="numeric" data-sort-initial="true"></th>
									<th style="width:35%" data-sort-ignore="true">{{ t('Ad') }}</th>
									<th style="width:35%" data-sort-ignore="true">{{ t('Message') }}</th>
									<th style="width:18%">{{ t('Date') }}</th>
									<th style="width:10%">{{ t('Option') }}</th>
								</tr>
								</thead>
								<tbody>
								<?php
								if (isset($messages) && $messages->count() > 0):
									foreach($messages as $key => $message):

									// Fixed 2
									if (empty($message->post)) continue;
									if (!$countries->has($message->post->country_code)) continue;

									// Post URL setting
									$postUrl = lurl(slugify($message->post->title) . '/' . $message->post->id . '.html');
								?>
								<tr>
									<td class="add-img-selector">
										<div class="checkbox">
											<label><input type="checkbox" name="message[]" value="{{ $message->id }}"></label>
										</div>
									</td>
									<td>
										@if ($message->reply_sent != 1)
											<strong><a href="{{ $postUrl }}">{{ $message->post->title }}</a></strong>
										@else
											<a href="{{ $postUrl }}">{{ $message->post->title }}</a>
										@endif
									</td>
									<td>
										{!! (!empty($message->filename) and \Storage::exists($message->filename)) ?
										'<i class="icon-attach-2"></i> ' : '' !!}
										{{ str_limit($message->message, 60) }}
									</td>
									<td>{{ $message->created_at->formatLocalized('%d/%m/%Y %H:%M') }}</td>
									<td class="action-td">
										<div>
											<p>
												<a class="btn btn-default btn-xs" href="{{ lurl('account/messages/' . $message->id . '/view') }}"
												   data-toggle="modal" data-target="#detailMessage{{ $message->id }}">
													<i class="icon-eye"></i> {{ t('View') }}
												</a>
											</p>
											<p>
												<a class="btn btn-danger btn-xs delete-action" href="{{ lurl('account/messages/' . $message->id . '/delete') }}">
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
						{{ (isset($messages)) ? $messages->links() : '' }}
					</div>

					<div style="clear:both"></div>

				</div>
			</div>
			<!--/.page-content-->

		</div>
		<!--/.row-->
	</div>
	<!--/.container-->
</div>
<!-- /.main-container -->

@if (isset($messages) && $messages->count() > 0)
	@foreach($messages as $key => $message)
		@continue(empty($message->post))
		@continue(!$countries->has($message->post->country_code))
		@include('account.inc.reply-message')
	@endforeach
@endif

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
