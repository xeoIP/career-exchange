$(document).ready(function()
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('input#locSearch').autocomplete({
        serviceUrl: siteUrl + '/ajax/countries/' + countryCode + '/cities/autocomplete',
        type: 'post',
        data: {
            'city': $(this).val(),
            '_token': $('input[name=_token]').val()
        },
        minChars: 1,
        onSelect: function(suggestion) {
            $('#lSearch').val(suggestion.data);
        }
    });
});
