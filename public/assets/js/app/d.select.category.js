$(document).ready(function()
{
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		async: false,
		cache: false
	});

    /* On load */
    $('#subCatBloc').hide();
	getSubCategories(siteUrl, languageCode, category, subCategory);

    /* On select */
    $('#parent').bind('click, change', function()
	{
		/* Get sub-categories */
		var category = $(this).val();
		getSubCategories(siteUrl, languageCode, category, 0);

		/* Check resume file field */
		var selectedCat = $(this).find('option:selected');
		var selectedCatType = selectedCat.data('type');

		/* Update 'parent_type' field */
		$('input[name=parent_type]').val(selectedCatType);
	});
});

function getSubCategories(siteUrl, languageCode, catId, selectedSubCatId)
{
	/* Check Bugs */
	if (typeof languageCode === 'undefined' || typeof catId === 'undefined') {
		return false;
	}

	/* Don't make ajax request if any category has selected. */
	if (catId == 0 || catId == '') {
		/* Remove all entries from subcategory field. */
		$('#category').empty().append('<option value="0">' + lang.select.subCategory + '</option>').val('0').trigger('change');
		return false;
	}

	/* Make ajax call */
	$.ajax({
		method: 'POST',
		url: siteUrl + '/ajax/category/sub-categories',
		data: {
			'_token': $('input[name=_token]').val(),
			'catId': catId,
			'selectedSubCatId': selectedSubCatId,
			'languageCode': languageCode
		}
	}).done(function(obj)
	{
		/* init. */
        $('#category').empty().append('<option value="0">' + lang.select.subCategory + '</option>').val('0').trigger('change');

		/* error */
        if (typeof obj.error !== "undefined") {
            $('#category').find('option').remove().end().append('<option value="0"> '+ obj.error.message +' </option>');
            $('#category').closest('.form-group').addClass('has-error');
            return false;
        } else {
            /* $('#category').closest('.form-group').removeClass('has-error'); */
        }

        if (typeof obj.subCats === "undefined" || typeof obj.countSubCats === "undefined") {
            return false;
        }

		/* Bind data into Select list */
        if (obj.countSubCats == 1) {
            $('#subCatBloc').hide();
            $('#category').empty().append('<option value="' + obj.subCats[0].tid + '">' + obj.subCats[0].name + '</option>').val(obj.subCats[0].tid).trigger('change');
        } else {
            $('#subCatBloc').show();
            $.each(obj.subCats, function (key, subCat) {
                if (selectedSubCatId == subCat.tid) {
                    $('#category').append('<option value="' + subCat.tid + '" selected="selected">' + subCat.name + '</option>');
                } else
                    $('#category').append('<option value="' + subCat.tid + '">' + subCat.name + '</option>');
            });
        }
	});

    return selectedSubCatId;
}
