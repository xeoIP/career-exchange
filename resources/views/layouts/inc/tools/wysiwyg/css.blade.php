{{-- Simditor --}}
@if (config('settings.simditor_wysiwyg'))
    <link media="all" rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/simditor/styles/simditor.css') }}" />
@endif

{{-- CKEditor --}}
{{-- Use this plugin by deactiving the "Simditor WYSIWYG Editor" --}}
@if (!config('settings.simditor_wysiwyg') && config('settings.ckeditor_wysiwyg'))
    {{-- ... --}}
@endif
