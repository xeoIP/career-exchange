$(document).ready(function()
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if (modalDefaultAdminCode != 0) {
        changeCity(countryCode, modalDefaultAdminCode);
    }
    $('#modalAdminField').change(function() {
        changeCity(countryCode, $(this).val());
    });
});

function changeCity(countryCode, modalDefaultAdminCode)
{
	/* Check Bugs */
    if (typeof languageCode == 'undefined' || typeof countryCode == 'undefined' || typeof modalDefaultAdminCode == 'undefined') {
        return false;
    }

	/* Make ajax call */
    $.ajax({
        method: 'POST',
        url: siteUrl + '/ajax/countries/' + countryCode + '/admin1/cities',
        data: {
            'languageCode': languageCode,
            'adminCode': modalDefaultAdminCode,
            'currSearch': $('#currSearch').val(),
            '_token': $('input[name=_token]').val()
        }
    }).done(function(data)
	{
        if (typeof data.adminCities == "undefined") {
            return false;
        }
        $('#selectedAdmin strong').html(data.selectedAdmin);
        $('#adminCities').html(data.adminCities);
        $('#modalAdminField').val(modalDefaultAdminCode).prop('selected');
    });
}
