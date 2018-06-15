<div class="navbar-custom-menu pull-left">
    <ul class="nav navbar-nav">
        <!-- =================================================== -->
        <!-- ========== Top menu items (ordered left) ========== -->
        <!-- =================================================== -->
        
        <li><a href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ __t('dashboard') }}</span></a></li>
        
        <!-- ========== End of top menu left items ========== -->
    </ul>
</div>


<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <!-- ========================================================= -->
        <!-- ========== Top menu right items (ordered left) ========== -->
        <!-- ========================================================= -->
        
        <li><a href="{{ url('/') }}" target="_blank"><i class="fa fa-home"></i> <span>{{ __t('Home') }}</span></a></li>
        
        @if (Auth::guest())
            <li><a href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/login') }}">{{ trans('admin::messages.login') }}</a></li>
        @else
            <li><a href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/logout') }}"><i class="fa fa-btn fa-sign-out"></i> {{ trans('admin::messages.logout') }}</a></li>
    @endif
    
    <!-- ========== End of top menu right items ========== -->
    </ul>
</div>
