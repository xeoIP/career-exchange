@if ($xPanel->hasAccess('update'))
	<a href="{{ url($xPanel->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-xs btn-default">
		<i class="fa fa-edit"></i> {{ trans('admin::messages.edit') }}
    </a>
@endif