@extends('layouts.master')

@section('content')
<div class="main-container">
	<div class="container">
		<div class="row">
			<div class="col-sm-3 page-sidebar">
				@include('account.inc.sidebar')
			</div>
			<!--/.page-sidebar-->

			<div class="col-sm-9 page-content">
				<div class="inner-box">
					<h2 class="title-2"><i class="icon-money"></i> {{ t('Transactions') }} </h2>

					<div style="clear:both"></div>

					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
							<tr>
								<th><span>ID</span></th>
								<th>{{ t('Description') }}</th>
								<th>{{ t('Payment Method') }}</th>
								<th>{{ t('Value') }}</th>
								<th>{{ t('Date') }}</th>
								<th>{{ t('Status') }}</th>
							</tr>
							</thead>
							<tbody>
							<?php
							if (isset($transactions) && $transactions->count() > 0):
								foreach($transactions as $key => $transaction):

									// Fixed 2
									if (empty($transaction->post)) continue;
									if (!$countries->has($transaction->post->country_code)) continue;

									// Get Package
									$package = \App\Models\Package::transById($transaction->package_id);
									if (empty($package)) continue;

									// Post URL setting
									$postUrl = lurl(slugify($transaction->post->title) . '/' . $transaction->post->id . '.html');

									// Currency
									$currency = \App\Models\Currency::find($package->currency_code);
									$currencySymbol = (!empty($currency)) ? $currency->symbol : '';
							?>
							<tr>
								<td>#{{ $transaction->id }}</td>
								<td><a href="{{ $postUrl }}">{{ $transaction->post->title }}</a><br>
									<strong>Ads Type</strong> {{ $package->short_name }} <br>
									<strong>Ads Duration</strong> {{ $package->duration }} days
								</td>
								<td>
									@if ($transaction->active == 1)
										@if (!empty($transaction->paymentMethod))
											{{ t('Paid by') }} {{ $transaction->paymentMethod->display_name }}
										@else
											{{ t('Paid by') }} --
										@endif
									@else
										{{ t('Pending payment') }}
									@endif
								</td>
								<td>{{ $currencySymbol . '' . $package->price }}</td>
								<td>{{ $transaction->created_at->formatLocalized('%d/%m/%Y %H:%M') }}</td>
								<td>
									@if ($transaction->active == 1)
										<span class="label label-success">{{ t('Done') }}</span>
									@else
										<span class="label label-info">{{ t('Pending') }}</span>
									@endif
								</td>
							</tr>
							<?php endforeach; ?>
							<?php endif; ?>
							</tbody>
						</table>
					</div>

					<div class="pagination-bar text-center">
						{{ (isset($transactions)) ? $transactions->links() : '' }}
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
@endsection

@section('after_scripts')
@endsection
