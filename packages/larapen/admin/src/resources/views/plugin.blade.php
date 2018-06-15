@extends('admin::layout')

@section('after_styles')
    <!-- Ladda Buttons (loading buttons) -->
    <link href="{{ asset('vendor/admin/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('header')
    <section class="content-header">
        <h1>
            {{ trans('admin::messages.Plugins') }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url(config('larapen.admin.route_prefix', 'admin').'/dashboard') }}">Admin</a></li>
            <li class="active">{{ trans('admin::messages.Plugins') }}</li>
        </ol>
    </section>
@endsection

@section('content')
    <!-- Default box -->
    <div class="box">
        <div class="box-body">
            
            <h3>{{ trans('admin::messages.Existing plugins') }}:</h3>
            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ trans('admin::messages.Name') }}</th>
                    <th>{{ trans('admin::messages.Description') }}</th>
                    <th class="text-right">{{ trans('admin::messages.Version') }}</th>
                    <th class="text-right">{{ trans('admin::messages.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($plugins as $key => $plugin)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $plugin->display_name }}</td>
                        <td>{{ $plugin->description }}</td>
                        <td class="text-right">{{ $plugin->version }}</td>
                        <td class="text-right">
                            @if ($plugin->has_installer)
                                @if ($plugin->installed)
                                    <a class="btn btn-xs btn-success" href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/plugin/' . $plugin->name . '/uninstall') }}">
                                        <i class="fa fa-toggle-on"></i> {{ trans('admin::messages.Uninstall') }}
                                    </a>
                                @else
                                    <a class="btn btn-xs btn-default" data-button-type="delete" href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/plugin/' . $plugin->name . '/install') }}">
                                        <i class="fa fa-toggle-off"></i> {{ trans('admin::messages.Install') }}
                                    </a>
                                @endif
                            @endif
                            <!--
                            <a class="btn btn-xs btn-danger" data-button-type="delete" href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/plugin/' . $plugin->name . '/delete') }}">
                                <i class="fa fa-trash-o"></i> {{ trans('admin::messages.delete') }}
                            </a>
                            -->
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div><!-- /.box-body -->
    </div><!-- /.box -->

@endsection

@section('after_scripts')
    <!-- Ladda Buttons (loading buttons) -->
    <script src="{{ asset('vendor/admin/ladda/spin.js') }}"></script>
    <script src="{{ asset('vendor/admin/ladda/ladda.js') }}"></script>

    <script>
        jQuery(document).ready(function($)
        {
            $(document).on('click', '.btn-xs', function() {
                var confirmation = confirm("<?php echo __t('Are you sure you want to perform this action?'); ?>");
                return confirmation;
            });
        });
    </script>
@endsection
