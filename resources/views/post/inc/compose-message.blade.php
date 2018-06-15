<div class="modal fade" id="applyJob" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">{{ t('Close') }}</span>
				</button>
				<h4 class="modal-title"><i class=" icon-mail-2"></i> {{ t('Contact Employer') }} </h4>
			</div>
			<form role="form" method="POST" action="{{ lurl('posts/' . $post->id . '/contact') }}" enctype="multipart/form-data">
				{!! csrf_field() !!}
				<div class="modal-body">

					@if (isset($errors) and count($errors) > 0 and old('messageForm')=='1')
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<ul class="list list-check">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					@if (Auth::check())
						<input type="hidden" name="name" value="{{ Auth::user()->name }}">
						@if (!empty(Auth::user()->email))
							<input type="hidden" name="email" value="{{ Auth::user()->email }}">
						@else
							<!-- email -->
							<div class="form-group required <?php echo (isset($errors) and $errors->has('email')) ? 'has-error' : ''; ?>">
								<label for="email" class="control-label">{{ t('E-mail') }}
									@if (!isEnabledField('phone'))
										<sup>*</sup>
									@endif
								</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="icon-mail"></i></span>
									<input id="email" name="email" type="text" placeholder="{{ t('i.e. you@gmail.com') }}"
										   class="form-control" value="{{ old('email', Auth::user()->email) }}">
								</div>
							</div>
						@endif
					@else
						<!-- name -->
						<div class="form-group required <?php echo (isset($errors) and $errors->has('name')) ? 'has-error' : ''; ?>">
							<label for="name" class="control-label">{{ t('Name') }} <sup>*</sup></label>
							<input id="name" name="name" class="form-control" placeholder="{{ t('Your name') }}" type="text"
								   value="{{ old('name') }}">
						</div>
							
						<!-- email -->
						<div class="form-group required <?php echo (isset($errors) and $errors->has('email')) ? 'has-error' : ''; ?>">
							<label for="email" class="control-label">{{ t('E-mail') }}
								@if (!isEnabledField('phone'))
									<sup>*</sup>
								@endif
							</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="icon-mail"></i></span>
								<input id="email" name="email" type="text" placeholder="{{ t('i.e. you@gmail.com') }}"
									   class="form-control" value="{{ old('email') }}">
							</div>
						</div>
					@endif
					
					<!-- phone -->
					<div class="form-group required <?php echo (isset($errors) and $errors->has('phone')) ? 'has-error' : ''; ?>">
						<label for="phone" class="control-label">{{ t('Phone Number') }}
							@if (!isEnabledField('email'))
								<sup>*</sup>
							@endif
						</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="icon-phone-1"></i></span>
							<input id="phone" name="phone" type="text"
								   placeholder="{{ t('Phone Number') }}"
								   maxlength="60" class="form-control" value="{{ old('phone', (Auth::check()) ? Auth::user()->phone : '') }}">
						</div>
					</div>
					
					<!-- message -->
					<div class="form-group required <?php echo (isset($errors) and $errors->has('message')) ? 'has-error' : ''; ?>">
						<label for="message" class="control-label">{{ t('Message') }} <span class="text-count">(500 max)</span> <sup>*</sup></label>
						<textarea id="message" name="message" class="form-control required" placeholder="{{ t('Your message here...') }}" rows="5">{{ old('message') }}</textarea>
					</div>

					<!-- filename -->
					<div class="form-group required <?php echo (isset($errors) and $errors->has('filename')) ? 'has-error' : ''; ?>">
						<label for="filename" class="control-label">{{ t('Resume') }} </label>
						<input id="filename" name="filename" type="file" class="file">
						<p class="help-block">{{ t('File types: :file_types', ['file_types' => showValidFileTypes('file')]) }}</p>
						@if (!empty($resume) and \Storage::exists($resume->filename))
							<div>
								<a class="btn btn-default" href="{{ \Storage::url($resume->filename) }}" target="_blank">
									<i class="icon-attach-2"></i> {{ t('Download current Resume') }}
								</a>
							</div>
						@endif
					</div>

					<!-- recaptcha -->
					@if (config('settings.activation_recaptcha'))
						<div class="form-group required <?php echo (isset($errors) and $errors->has('g-recaptcha-response')) ? 'has-error' : ''; ?>">
							<label class="control-label" for="g-recaptcha-response">{{ t('We do not like robots') }}</label>
							<div>
								{!! Recaptcha::render(['lang' => config('app.locale')]) !!}
							</div>
						</div>
					@endif
					
					<input type="hidden" name="country" value="{{ $country->get('code') }}">
					<input type="hidden" name="post" value="{{ $post->id }}">
					<input type="hidden" name="messageForm" value="1">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{{ t('Cancel') }}</button>
					<button type="submit" class="btn btn-success pull-right">{{ t('Send message') }}</button>
				</div>
			</form>
		</div>
	</div>
</div>
@section('after_styles')
	@parent
	<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
	<style>
		.krajee-default.file-preview-frame:hover:not(.file-preview-error) {
			box-shadow: 0 0 5px 0 #666666;
		}
	</style>
@endsection

@section('after_scripts')
	@parent
	
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
	@if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js'))
		<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js') }}" type="text/javascript"></script>
	@endif
	
	<script>
		/* Initialize with defaults (Resume) */
		$('#filename').fileinput(
		{
            language: '{{ config('app.locale') }}',
			showPreview: false,
			allowedFileExtensions: {!! getUploadFileTypes('file', true) !!},
			showUpload: false,
			showRemove: false,
			maxFileSize: {{ (int)config('settings.upload_max_file_size', 1000) }}
		});
	</script>
	<script>
		$(document).ready(function () {
			@if (count($errors) > 0)
				@if (count($errors) > 0 and old('messageForm')=='1')
					$('#applyJob').modal();
				@endif
			@endif
		});
	</script>
@endsection