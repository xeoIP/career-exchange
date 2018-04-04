/* exported StepOne */
var StepOne = new function () {
    var _cityList;

    var typeAheadLocationPreferences = function () {
        $.typeahead({
            input: '.js-typeahead-city',
            order: 'desc',
            source: {
                data: _cityList,
                minLength: 2,
                maxItem: 3
            }
        });
    };

    var photoUploadPreview = function () {
        $('#myPhotoSelector').on('change', function (event) {
            var profilePicturePreview = document.getElementById('profilePicturePreview');
            profilePicturePreview.src = URL.createObjectURL(event.target.files[0]);
        });
    };

    return {
        init: function (cities) {
            _cityList = cities;

            typeAheadLocationPreferences();
            photoUploadPreview();
        }
    };
}();


