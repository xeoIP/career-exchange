/* exported StepThree */
var StepThree = new function () {

    var _insertCityInputCounter;
    var _cityList;
    var _locations;
    var _insertedCities =0;
    var _repopulateCityList;

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

    var insertCityInput = function () {
        $('#addCityInputBtn').on('click', function () {
            if(_insertedCities<=3) {
                _insertCityInputCounter += 1;
                _insertedCities += 1;

                $('#locationPreferencesDiv').append(
                    '<div class="typeahead__container" id="insertCityInputId' + _insertCityInputCounter + '">' +
                    '<div class="typeahead__field">' +
                    '<span class="typeahead__query">' +
                    '<input class="js-typeahead-city" name="locations[]" type="search" placeholder="Search" autocomplete="off"' +
                    '</span>' +
                    '</div>' +
                    '</div>'
                );

                $('#delIconForCityDiv').append(
                    '<btn class="close col-sm-12 delSkillInputBtn" aria-label="Close" id="' + _insertCityInputCounter + '">' +
                    '<span class="glyphicon glyphicon-trash" aria-hidden ="true"/>' +
                    '</btn>'
                );

                typeAheadLocationPreferences();
            }
        });
    };

    var delCityInput = function () {
        $(document).on('click', '.delSkillInputBtn', function () {

            document.getElementById('insertCityInputId' + this.id).remove();
            this.remove();
            _insertedCities-=1;
        });
    };

    var initializeLocations = function () {
        console.log();
        if(_repopulateCityList.length !== 0)
        {
            $.each(_repopulateCityList.slice(1), function (index, value) {
                $('#addCityInputBtn').trigger('click');
                var preferredLocations = $('#locationPreferencesDiv');
                preferredLocations.find('input:last').val(value);
            })
        }
        else if (_locations !== null) {
            var userLocations = _locations.slice(1);
            $.each(userLocations, function (index, value) {
                $('#addCityInputBtn').trigger('click');
                var preferredLocations = $('#locationPreferencesDiv');
                preferredLocations.find('input:last').val(value);
            });
        }
    };

    var handleCurrentCompensation = function () {
        $('#base_salaryRadio, #contract_rateRadio').on('click', function () {
           $('#base_salaryDiv').toggleClass('hidden');
           $('#contract_rateDiv').toggleClass('hidden');
        });
    };

    return {
        init: function (cities, locations, repopulateCityList) {
            _insertCityInputCounter = 0;
            _cityList = cities;
            _locations = jQuery.parseJSON(locations);
            _repopulateCityList = repopulateCityList;

            typeAheadLocationPreferences();
            insertCityInput();
            delCityInput();
            initializeLocations();
            handleCurrentCompensation();
        }
    };
}();


