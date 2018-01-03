@extends('admin::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ trans('admin::messages.dashboard') }}<small>{{ trans('admin::messages.first_page_you_see') }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url(config('larapen.admin.route_prefix', 'admin')) }}">{{ config('app.name') }}</a></li>
        <li class="active">{{ trans('admin::messages.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('admin::inc._dashboard')
        </div>
    </div>
@endsection
