@if (isset($message) and !empty($message))
<div class="modal fade" id="detailMessage{{ $message->id }}" tabindex="-1" role="dialog" aria-labelledby="detailMessage{{ $message->id }}Label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="detailMessage{{ $message->id }}Label">{{ t('Message for') . ' "' . $message->post->title . '"' }}</h4>
			</div>
			<form role="form" method="POST" action="{{ lurl('account/messages/' . $message->id . '/reply') }}">
				{!! csrf_field() !!}
				<div class="modal-body">
					@if (isset($errors) and count($errors) > 0 and old('post_id')==$message->post->id)
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<ul class="list list-check">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					
					<strong>{{ t("Sender's Name") }}:</strong> {{ $message->name }}<br>
					<strong>{{ t("Sender's Email") }}:</strong> {{ $message->email or '--' }}<br>
					<strong>{{ t("Sender's Phone") }}:</strong> {{ $message->phone or '--' }}<br>
					<strong>{{ t("Message") }}:</strong><br>
					{!! nl2br($message->message) !!}
					{!! (!empty($message->filename) and \Storage::exists($message->filename)) ?
						' <br><br><a class="btn btn-info" href="' .\Storage::url($message->filename) . '">' . t('Download') . '</a>' : '' !!}
					
					<hr>
					
					@if ($message->reply_sent != 1)
					<!-- Message -->
					<div class="form-group required <?php echo (isset($errors) and $errors->has('message')) ? 'has-error' : ''; ?>">
						<label for="message" class="control-label">{{ t('Message') }} <span class="text-count">(500 max)</span> <sup>*</sup></label>
						<textarea name="message" class="form-control required" placeholder="{{ t('Your message here...') }}" rows="5">{{ old('message') }}</textarea>
					</div>
					@else
						<span class="text-warning">{{ t('You have already answered this message.') }}</span>
					@endif
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ t('Close') }}</button>
					@if ($message->reply_sent != 1)
						<button type="submit" class="btn btn-primary"><i class="icon-reply"></i> {{ t('Reply') }}</button>
					@endif
				</div>
				<input type="hidden" name="post_id" value="{{ $message->post->id }}">
				<input type="hidden" name="post_title" value="{{ $message->post->title }}">
				<input type="hidden" name="sender_name" value="{{ $user->name }}">
				<input type="hidden" name="sender_email" value="{{ $user->email }}">
				<input type="hidden" name="sender_phone" value="{{ $user->phone }}">
			</form>
		</div>
	</div>
</div>
@endif

@section('after_scripts')
	@parent
	
	@if (isset($message) and !empty($message))
	<script>
		$(document).ready(function () {
			@if (count($errors) > 0)
				@if (count($errors) > 0 and old('post_id')==$message->post->id)
					$('#detailMessage{{ $message->id }}').modal();
			@endif
			@endif
		});
	</script>
	@endif
@endsection