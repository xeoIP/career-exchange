<?php
$advertising = \App\Models\Advertising::where('slug', 'bottom')->first();
?>
@if (!is_null($advertising))
	<div class="container hidden-md hidden-sm hidden-xs mt20" style="margin-bottom: 20px;">
		<div class="text-center">
			{!! $advertising->tracking_code_large !!}
		</div>
	</div>
	<div class="container hidden-lg hidden-xs">
		<div class="text-center">
			{!! $advertising->tracking_code_medium !!}
		</div>
	</div>
	<div class="container hidden-sm hidden-md hidden-lg">
		<div class="text-center">
			{!! $advertising->tracking_code_small !!}
		</div>
	</div>
@endif