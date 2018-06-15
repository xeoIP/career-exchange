@extends('install.layouts.master')

@section('title', trans('messages.cron_jobs'))

@section('content')

    <h3 class="title-3 text-success">
        <i class="icon-check"></i> Congratulations, you've successfully installed the CareerExchange portal.
    </h3>

    Remember that all your configurations were saved in <strong class="text-bold">[APP_ROOT]/.env</strong> file. You can change it when needed.
    <br /><br />
    Now, you can go to your Admin Panel with link:
    <a class="text-bold" href="{{ url(config('larapen.admin.route_prefix', 'admin')) }}">{{ url(config('larapen.admin.route_prefix', 'admin')) }}</a>.
    Visit your website: <a class="text-bold" href="{{ url('/') }}" target="_blank">{{ url('/') }}</a>
    <br><br>

    The CareerExchange Team.
    <div class="clearfix"><!-- --></div>
    <br />

@endsection

@section('after_scripts')
    <script type="text/javascript" src="{{ URL::asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@endsection
