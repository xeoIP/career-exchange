@extends('layouts.master')

@section('content')
	<div class="main-container">
		<div class="container">
			<div class="row">
				<div class="col-sm-3 page-sidebar">
					@include('account/inc/sidebar')
				</div>
				<!--/.page-sidebar-->

				<div class="col-sm-9 page-content">
					<div class="inner-box">
						<h2 class="title-2"><i class="icon-cancel-circled "></i> {{ t('Close account') }} </h2>
						<p>{{ t('You are sure you want to close your account?') }}</p>

						@if ($user->is_admin)
							<span style="color: red; font-weight: bold;">Admin users can't be deleted by this way.</span>
						@else
							<form role="form" method="POST" action="{{ lurl('account/close') }}">
								{!! csrf_field() !!}
								<div>
									<label class="radio-inline">
										<input type="radio" name="close_account_confirmation" id="closeAccountConfirmation1" value="1"> {{ t('Yes') }}
									</label>
									<label class="radio-inline">
										<input type="radio" name="close_account_confirmation" id="closeAccountConfirmation0" value="0" checked> {{ t('No') }}
									</label>
								</div>
								<br>
								<button type="submit" class="btn btn-primary">{{ t('Submit') }}</button>
							</form>
						@endif

					</div>
					<!--/.inner-box-->
				</div>
				<!--/.page-content-->

			</div>
			<!--/.row-->
		</div>
		<!--/.container-->
	</div>
	<!-- /.main-container -->
@endsection
