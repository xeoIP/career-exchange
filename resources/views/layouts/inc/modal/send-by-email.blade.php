<div class="modal fade" id="sendByEmail" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">{{ t('Close') }}</span>
				</button>
				<h4 class="modal-title">
					<i class="fa icon-info-circled-alt"></i>
					{{ t('Send by Email') }}
				</h4>
			</div>
			<form role="form" method="POST" action="{{ lurl('send-by-email') }}">
				<div class="modal-body">

					@if (isset($errors) and count($errors) > 0 and old('sendByEmailForm')=='1')
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<ul class="list list-check">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					{!! csrf_field() !!}

					<!-- Sender Email -->
					@if (Auth::check())
						<input type="hidden" name="sender_email" value="{{ $user->email }}">
					@else
						<div class="form-group required <?php echo (isset($errors) and $errors->has('sender_email')) ? 'has-error' : ''; ?>">
							<label for="sender_email" class="control-label">{{ t('Your Email') }} <sup>*</sup></label>
							<div class="input-group">
								<span class="input-group-addon"><i class="icon-mail"></i></span>
								<input id="sender_email" name="sender_email" type="text" maxlength="60" class="form-control" value="{{ old('sender_email') }}">
							</div>
						</div>
					@endif

					<!-- Recipient Email -->
					<div class="form-group required <?php echo (isset($errors) and $errors->has('recipient_email')) ? 'has-error' : ''; ?>">
						<label for="recipient_email" class="control-label">{{ t('Recipient Email') }} <sup>*</sup></label>
						<div class="input-group">
							<span class="input-group-addon"><i class="icon-mail"></i></span>
							<input id="recipient_email" name="recipient_email" type="text" maxlength="60" class="form-control" value="{{ old('recipient_email') }}">
						</div>
					</div>

					<input type="hidden" name="post" value="{{ old('post') }}">
					<input type="hidden" name="sendByEmailForm" value="1">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ t('Cancel') }}</button>
					<button type="submit" class="btn btn-primary">{{ t('Send') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>