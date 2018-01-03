<!-- read_images -->
<div @include('admin::panel.inc.field_wrapper_attributes') >

	<input type="hidden" name="edit_url" value="{{ Request::url() }}">
	<label>{{ $field['label'] }}</label>
	<?php
        $entity_model = (isset($field['value'])) ? $field['value'] : null;
        $posts_pictures_number = (int)config('settings.ads_pictures_number');
	?>

	<div style="display: block; text-align: center;">
	@if (!empty($entity_model) && !$entity_model->isEmpty())
		@foreach ($entity_model as $connected_entity_entry)
			<div style="margin: 10px 5px; display: inline-block;">
				<img src="{{ \Storage::disk($field['disk'])->url($connected_entity_entry->{$field['attribute']}) }}" style="width:320px; height:auto;">
				<div style="text-align: center; margin-top: 10px;">
					<a href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/picture/' . $connected_entity_entry->id . '/edit') }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> Edit</a>&nbsp;
					<a href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/picture/' . $connected_entity_entry->id) }}" class="btn btn-xs btn-default" data-button-type="delete"><i class="fa fa-trash"></i> Delete</a>
				</div>
			</div>
		@endforeach
        @if ($entity_model->count() < $posts_pictures_number)
            <hr><br>
            <a href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/picture/create?post_id=' . Request::segment(3)) }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {{ trans('admin::messages.add') }} picture</a>
        @endif
	@else
		No pictures found.<br>
        <a href="{{ url(config('larapen.admin.route_prefix', 'admin') . '/picture/create?post_id=' . Request::segment(3)) }}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i> {{ trans('admin::messages.add') }} picture</a>
	@endif
	</div>
	<div style="clear: both;"></div>

</div>

@if ($xPanel->checkIfFieldIsFirstOfItsType($field, $fields))
    @push('crud_fields_scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("[data-button-type=delete]").click(function (e) {
            e.preventDefault(); // does not go through with the link.

            var $this = $(this);

            if (confirm("{{ trans('admin::messages.delete_confirm') }}") == true) {
                $.post({
                    type: 'DELETE',
                    url: $this.attr('href'),
                    success: function (result) {
                        alert("{{ trans('admin::messages.delete_confirmation_message') }}");
                        window.location.replace("{{ url($xPanel->route) }}");
                        window.location.href = "{{ url($xPanel->route) }}";
                    }
                });
            }
        });
    </script>
    @endpush
@endif