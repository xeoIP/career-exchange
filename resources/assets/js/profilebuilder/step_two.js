/* exported StepTwo */
var StepTwo = new function () {
    var _positions;
    var _roles;
    var _additionalSkills;
    var _roleButtonsCounter;
    var _additionalSkillsCounter;
    var _nextAdditionalId = 0;

    var handleButtonsForRole = function () {
        $('#position').on('change', function () {
            _roleButtonsCounter = 0;

            //remove all buttons from div if exist
            $('#roles').empty();
            $('#experience').empty();

            var roles = $(this).find('option:selected').data('roles');
            $.each(roles, function (index, value) {

                var roleButton = $('<checkbox id="roleId' + value.id + '" class="btn btn-default btn-role" ' +
                    'value="' + value.id + '">' + value.name + '</checkbox>');
                $('#roles').append(roleButton);
                roleButton.click(function () {
                    var buttonId = $(this).attr('id');
                    $(this).toggleClass('active');

                    if ($(this).hasClass('active')) {
                        _roleButtonsCounter++;
                        createExperienceLabelDropDown($(this).text(), buttonId, $(this).attr('value'), 'role_experience');
                    } else {
                        _roleButtonsCounter--;
                        var divRoleId = 'divRoleId' + buttonId;
                        document.getElementById(divRoleId).remove();
                    }

                    if (_roleButtonsCounter >= 3) {
                        $(this).parent().find('.btn-role').not('.active').addClass('disabled').css('pointer-events', 'none');
                    } else {
                        $(this).parent().find('.btn-role').not('.active').removeClass('disabled').css('pointer-events', 'auto');
                    }
                });
            });

            //add mandatory position experience when position is chosen  -- >
            var defaultDiv = document.getElementById('divRoleIdFirst');

            if (defaultDiv !== null) {
                defaultDiv.remove();//if there is any positions remove them
            }

            var selectedOption = $('#position').find(':selected').text();

            createExperienceLabelDropDown(selectedOption, 'First', false, 'position[]');
        });
    };

    function createExperienceLabelDropDown(label, id, value, name) {
        var roleName = (value !== false) ? name + '[' + value + ']' : name;
        var selectExperienceDiv = $('' +
            '<div class="col-sm-12" id="divRoleId' + id + '">' +
            '<label class="col-sm-4">' + label + '</label>' +
            '<select class="form-control select-input btn-mini col-sm-6" id="positionExperience" name="' + roleName + '">' +
            '<option value="0">0-1 years</option>' +
            '<option value="1">1-2 years</option>' +
            '<option value="2">2-4 years</option>' +
            '<option value="3">4-6 years</option>' +
            '<option value="4">6-8 years</option>' +
            '<option value="5">8-10 years</option>' +
            '<option value="6">10+ years</option>' +
            '</select>' +
            '<hr class="col-sm-5" style="margin-bottom: 1%;">' +
            '</div>' +
            '');
        $('#experience').append(selectExperienceDiv);
    }

    var initializePositionsAndRoles = function () {
        if (_positions !== null) {
            $('#position').trigger('change');
            $('#divRoleIdFirst').find('option[value="' + _positions[1] + '"]').prop('selected', true);
            if (_roles !== null) {
                $.each(_roles, function (index, value) {
                    $('#roleId' + index).trigger('click');
                    $('#divRoleIdroleId' + index).find('option[value="' + value + '"]').prop('selected', true);
                });
            }
        }
    };

    var addAdditionalSkills = function () {
        $('#addSkillBtn').on('click', function () {

            if (_additionalSkillsCounter <= 4) {

                _additionalSkillsCounter += 1;

                var additionalId = (_nextAdditionalId !== 0) ? _nextAdditionalId : _additionalSkillsCounter;
                var additionalSkills = $('' +
                    '<div id="SkillInpId' + additionalId + '" class="col-sm-8">' +
                    '<input class="form-control" type="text" name="additional_skill_' + additionalId + '[]" ' +
                    'placeholder="what is the hardest skill that you know?">' +
                    '</div>' +
                    '<div id="skillDropDownContainerId' + additionalId + '" class="col-sm-3">' +
                    '<select id="experienceDropDownId1" class="form-control col-sm-6" name="additional_skill_' + additionalId + '[]">' +
                    '<option value="0">0-1 years</option>' +
                    '<option value="1">1-2 years</option>' +
                    '<option value="2">2-4 years</option>' +
                    '<option value="3">4-6 years</option>' +
                    '<option value="4">6-8 years</option>' +
                    '<option value="5">8-10 years</option>' +
                    '<option value="6">10+ years</option>' +
                    '</select>' +
                    '</div>' +
                    '<btn class="close col-sm-1 deleteBtn glyphicon glyphicon-trash" id="' + additionalId + '">' +
                    '<span aria-hidden="true"></span>' +
                    '</btn>'
                );

                $('#additionalSkills').append(additionalSkills);
            }
        });
    };

    var removeAdditionalSkill = function () {
        $(document).on('click', '.deleteBtn', function () {
            document.getElementById('SkillInpId' + this.id).remove();
            document.getElementById('skillDropDownContainerId' + this.id).remove();
            this.remove();
            _nextAdditionalId = this.id;
            _additionalSkillsCounter -= 1;
        });
    };

    var initializeAdditionalSkills = function () {
        if (_additionalSkills !== null) {
            var skills = _additionalSkills.slice(1);
            $.each(skills, function (index, value) {
                $('#addSkillBtn').trigger('click');
                var additionalSkills = $('#additionalSkills');
                additionalSkills.find('input:last').val(value[0]);
                additionalSkills.find('select:last').find('option[value="' + value[1] + '"]').prop('selected', true);
            });
        }
    };

    return {
        init: function (positions, roles, additionalSkills) {
            _positions = jQuery.parseJSON(positions);
            _roles = jQuery.parseJSON(roles);
            _additionalSkills = jQuery.parseJSON(additionalSkills);
            _roleButtonsCounter = 0;
            _additionalSkillsCounter = 1;

            handleButtonsForRole();
            initializePositionsAndRoles();

            addAdditionalSkills();
            removeAdditionalSkill();
            initializeAdditionalSkills();
        }
    };
}();