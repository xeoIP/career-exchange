$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
        cache: false
    });

    /* Get and Bind administrative divisions */
    getAdminDivisions(countryCode, adminType, selectedAdminCode);
    $('#country').bind('click, change', function() {
        getAdminDivisions($(this).val(), adminType, 0);
    });

    /* Clear administrative divisions */
    $('#adminCode').bind('click, change', function() {
        $('#city').empty().append('<option value="0">' + lang.select.city + '</option>').val('0').trigger('change');
    });

    /* Get and Bind the selected city */
    getSelectedCity(countryCode, cityId);

    /* Get and Bind cities */
    $('#city').select2({
        ajax: {
            url: function () {
                /* Get the current admin code */
                var selectedAdminCode = $('#adminCode').val();
                if (typeof selectedAdminCode == "undefined") {
                    selectedAdminCode = 0;
                }
                return siteUrl + '/ajax/countries/' + countryCode + '/admins/' + adminType + '/' + selectedAdminCode + '/cities';
            },
            dataType: 'json',
            delay: 50,
            data: function (params) {
                var query = {
                    languageCode: languageCode,
                    q: params.term, // search term
                    page: params.page
                };

                return query;
            },
            processResults: function (data, params) {
                /*
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                */
                params.page = params.page || 1;

                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 10) < data.totalEntries
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, /* let our custom formatter work */
        minimumInputLength: 2,
        templateResult: function (data) {
            return data.text;
        },
        templateSelection: function (data, container) {
            return data.text;
        }
    });
});

/**
 * Get and Bind Administrative Divisions
 *
 * @param countryCode
 * @param adminType
 * @param selectedAdminCode
 * @returns {*}
 */
function getAdminDivisions(countryCode, adminType, selectedAdminCode)
{
    if (countryCode==0 || countryCode == '') return false;

    $.get(siteUrl + '/ajax/countries/' + countryCode + '/admins/' + adminType + '?languageCode=' + languageCode, function(obj)
    {
        /* init. */
        $('#adminCode').empty().append('<option value="0">' + lang.select.admin + '</option>').val('0').trigger('change');
        $('#city').empty().append('<option value="0">' + lang.select.city + '</option>').val('0').trigger('change');

        /* Bind data into Select list */
        if (typeof obj.error != "undefined") {
            $('#adminCode').find('option').remove().end().append('<option value="0"> '+ obj.error.message +' </option>');
            $('#adminCode').closest('.form-group').addClass('has-error');
            return false;
        } else {
            $('#adminCode').closest('.form-group').removeClass('has-error');
        }

        if (typeof obj.data == "undefined") {
            return false;
        }
        $.each(obj.data, function (key, item) {
            if (selectedAdminCode == item.code) {
                $('#adminCode').append('<option value="' + item.code + '" selected="selected">' + item.name + '</option>');
            } else {
                $('#adminCode').append('<option value="' + item.code + '">' + item.name + '</option>');
            }
        });
    });

    return selectedAdminCode;
}

/**
 * Get and Bind City by ID
 *
 * @param countryCode
 * @param cityId
 */
function getSelectedCity(countryCode, cityId)
{
    $.get(siteUrl + '/ajax/countries/' + countryCode + '/cities/' + cityId + '?languageCode=' + languageCode, function(data) {
        $('#city').empty().append('<option value="' + data.id + '">' + data.text + '</option>').val(data.id).trigger('change');
    }).fail(function() {
        $('#city').empty().append('<option value="0">' + lang.select.city + '</option>').val('0').trigger('change');
    });
}
