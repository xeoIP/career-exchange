/* exported StepFive */
var StepFive = new function () {

    var _additionalExperienceCounter = 0;
    var _additionalEducationCounter = 0;
    var _experiences;
    var _educations;

    var yearSelect = function () {
        $('#educationDiv').find('.hasYear').yearselect({
            start: (new Date()).getFullYear() - 50,
            end: (new Date()).getFullYear()
        }).attr('style', 'margin-bottom: 5%;');
    };

    var fillOfImport = function () {

        $('input[type=radio][name=importResume]').change(function () {
            if (this.value === 'importResume') {
                //hide
                $('#workExperienceDiv').attr('style', 'display:none');
                $('#educationDiv').attr('style', 'display:none');
                //show
                $('#importResumeDiv').attr('style', 'display:block');

            } else {
                //show
                $('#workExperienceDiv').attr('style', 'display:block');
                $('#educationDiv').attr('style', 'display:block');
                //hide
                $('#importResumeDiv').attr('style', 'display:none');
            }
        });
    };

    var addAdditionalExperience = function () {
        $('#addExperience').on('click', function () {
            if (_additionalExperienceCounter < 4) {
                var additionalExperience = $('' +
                    '<div class="col-sm-12 lineInput">' +
                    '<h5> Work Experience </h5>' +
                    '<input type="text" name="company[]" class="form-control" placeholder="Company Name">' +
                    '<input type="text" name="title[]" class="form-control" placeholder="Title">' +
                    '<div class="row">' +
                    '<div class="col-sm-6">' +
                    '<small> Start Date (M/Y)</small>' +
                    '<input type="date" name="start_date[]" class="form-control" placeholder="Start Date (M/Y)">' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<small> End Date (M/Y)</small>' +
                    '<input type="date" name="end_date[]" class="form-control" placeholder="End Date (M/Y)">' +
                    '</div>' +
                    '</div>' +
                    '<label class="currentExperience">Current' +
                    '<input type="hidden" value="0" name="current[]">' +
                    '<input type="checkbox" value="1" name="current[]">' +
                    '<span class="checkmark"></span>' +
                    '</label>' +
                    '<button class="btn btn-block col-sm-12 experienceRemove" onclick=" $(this).closest(\'div\').remove();" style="margin-bottom: 20px"> Delete </button>' +
                    '</div>'
                );

                $('#dynamicExperience').append(additionalExperience);
                toggleCheckboxAsRadio();
                _additionalExperienceCounter++;
            }
        });

        $(document).on('click', '.experienceRemove', function () {
            _additionalExperienceCounter -= 1;
        });

    };

    var addAdditionalEducation = function () {
        $('#addEducation').on('click', function () {
            $('#educationDiv').find('.hasYear').removeClass('hasYear');
            if (_additionalEducationCounter < 3) {
                var additionalEducation = $('' +
                    '<div class="col-sm-12 lineInput">' +
                    '<h5>Education</h5>' +
                    '<input type="text" name="university[]" class="form-control" placeholder="University">' +
                    '<input type="text" name="degree[]" class="form-control" placeholder="Degree">' +
                    '<small>Year</small>' +
                    '<input class="yearselect hasYear form-control" name="degree_date[]" value="2016">' +
                    '<button class="btn btn-block col-sm-12" onclick=" $(this).closest(\'div\').remove();_additionalEducationCounter--" style="margin-bottom: 20px"> Delete </button>' +
                    '</div>'
                );

                $('#dynamicEducation').append(additionalEducation);

                yearSelect();
                _additionalEducationCounter++
            }
        });
    };

    var toggleCheckboxAsRadio = function () {
        $('input:checkbox[name="current[]"]').click(function () {

            $('input[type=date]').each(function () {
                $(this).removeClass('hidden');
            });

            $(this.closest('div')).find('input[name="end_date[]"]').addClass('hidden');

            $('input:checkbox[name="current[]"]').not(this).prop('checked', false);
            if (!$(this).is(':checked')) {
                $(this).prop('checked', false);
                $(this.closest('div')).find('input[name="end_date[]"]').removeClass('hidden');
            } else {
                $(this).prop('checked', true);
            }

        });
    };

    var initializeWorkExperience = function () {

        if (_experiences.hasOwnProperty('company')) {
            var experienceCount = _experiences['company'].length;

            for (var counter = 1; counter < experienceCount; counter++) {
                $('#addExperience').trigger('click');
                var dynamicExperienceDiv = $('#dynamicExperience');
                dynamicExperienceDiv.find('input[name="company[]"]').last().val(_experiences['company'][counter]);
                dynamicExperienceDiv.find('input[name="title[]"]').last().val(_experiences['title'][counter]);
                dynamicExperienceDiv.find('input[name="start_date[]"]').last().val(_experiences['start_date'][counter]);
                dynamicExperienceDiv.find('input[name="end_date[]"]').last().val(_experiences['end_date'][counter]);

                if (_experiences['current'][counter] == 1) {
                    dynamicExperienceDiv.find('input[name="current[]"]').last().trigger('click');
                }
            }
        }
    };

    var initializeEducations = function () {
        if (_educations.hasOwnProperty('university')) {
            var educationCount = _educations['university'].length;

            for (var educationCounter = 1; educationCounter < educationCount; educationCounter++) {
                $('#addEducation').trigger('click');
                var dynamicEducationDiv = $('#educationDiv');
                dynamicEducationDiv.find('input[name="university[]"]').last().val(_educations['university'][educationCounter]);
                dynamicEducationDiv.find('input[name="degree[]"]').last().val(_educations['degree'][educationCounter]);
                dynamicEducationDiv.find('select:last').find('option[value="' + _educations['degree_date'][educationCounter] + '"]').prop('selected', true);
            }
        }
    };

    var submitForm = function () {
        $('#submit_step5').on('click', function () {
            if ($('input[name=importResume]:checked').val() === 'createResume') {
                $('form#create_resume_form').submit();
            } else {
                $('form#import_resume_form').submit();
            }
        });
    };

    return {
        init: function (experiences, educations) {
            _experiences = JSON.parse(experiences);
            _educations = JSON.parse(educations);

            yearSelect();
            fillOfImport();
            addAdditionalExperience();
            addAdditionalEducation();
            toggleCheckboxAsRadio();
            initializeWorkExperience();
            initializeEducations();
            submitForm();
        }
    };
}();
