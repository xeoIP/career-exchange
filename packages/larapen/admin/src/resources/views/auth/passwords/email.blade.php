@extends('admin::auth.layout')

@section('content')
    
    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('admin::messages.reset_password') }}</p>
    
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        
        <form action="{{ url(config('larapen.admin.route_prefix', 'admin') . '/password/email') }}" method="post">
            {!! csrf_field() !!}
            
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="{{ trans('admin::messages.email_address') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
    
            @if (config('settings.activation_recaptcha'))
                <!-- g-recaptcha-response -->
                <div class="form-group required <?php echo (isset($errors) and $errors->has('g-recaptcha-response')) ? 'has-error' : ''; ?>">
                    <div class="no-label">
                        {!! Recaptcha::render(['lang' => config('app.locale')]) !!}
                    </div>
	
					@if ($errors->has('g-recaptcha-response'))
						<span class="help-block">
                        	<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                    	</span>
					@endif
                </div>
            @endif
            
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('admin::messages.send_reset_link') }}</button>
                </div>
                <!-- /.col -->
            </div>
            
        </form>
        
        <br>
        <a href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/login') }}">{{ trans('admin::messages.login') }}</a><br>
    
    </div>
    <!-- /.login-box-body -->
    
@endsection
