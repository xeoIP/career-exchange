<?php
$var_name = str_replace('[]', '', $field['name']);
$var_name = str_replace('][', '.', $var_name);
$var_name = str_replace('[', '.', $var_name);
$var_name = str_replace(']', '', $var_name);
$required = (isset($field['rules']) && isset($field['rules'][$var_name]) && in_array('required', explode('|', $field['rules'][$var_name]))) ? true : '';
?>
@if (isset($field['wrapperAttributes']))
    @foreach ($field['wrapperAttributes'] as $attribute => $value)
    	@if (is_string($attribute))
        {{ $attribute }}="{{ $value }}"
        @endif
    @endforeach

    @if (!isset($field['wrapperAttributes']['class']))
		class="form-group col-md-12{{ $errors->has($var_name) ? ' has-error' : '' }}"
    @endif
@else
	class="form-group col-md-12{{ $errors->has($var_name) ? ' has-error' : '' }}"
@endif