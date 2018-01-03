{{-- localized date using jenssegers/date --}}
<td data-order="{{ $entry->{$column['name']} }}">
	{{ Date::parse($entry->{$column['name']})->format(config('larapen.admin.default_date_format')) }}
</td>