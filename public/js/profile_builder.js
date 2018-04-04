var StepOne=new function(){var e,t=function(){$.typeahead({input:".js-typeahead-city",order:"desc",source:{data:e,minLength:2,maxItem:3}})},n=function(){$("#myPhotoSelector").on("change",function(e){document.getElementById("profilePicturePreview").src=URL.createObjectURL(e.target.files[0])})};return{init:function(i){e=i,t(),n()}}},StepTwo=new function(){function e(e,t,n,i){var a=!1!==n?i+"["+n+"]":i,o=$('<div class="col-sm-12" id="divRoleId'+t+'"><label class="col-sm-4">'+e+'</label><select class="form-control select-input btn-mini col-sm-6" id="positionExperience" name="'+a+'"><option value="0">0-1 years</option><option value="1">1-2 years</option><option value="2">2-4 years</option><option value="3">4-6 years</option><option value="4">6-8 years</option><option value="5">8-10 years</option><option value="6">10+ years</option></select><hr class="col-sm-5" style="margin-bottom: 1%;"></div>');$("#experience").append(o)}var t,n,i,a,o,l=0,c=function(){$("#position").on("change",function(){a=0,$("#roles").empty(),$("#experience").empty();var t=$(this).find("option:selected").data("roles");$.each(t,function(t,n){var i=$('<checkbox id="roleId'+n.id+'" class="btn btn-default btn-role" value="'+n.id+'">'+n.name+"</checkbox>");$("#roles").append(i),i.click(function(){var t=$(this).attr("id");if($(this).toggleClass("active"),$(this).hasClass("active"))a++,e($(this).text(),t,$(this).attr("value"),"role_experience");else{a--;var n="divRoleId"+t;document.getElementById(n).remove()}a>=3?$(this).parent().find(".btn-role").not(".active").addClass("disabled").css("pointer-events","none"):$(this).parent().find(".btn-role").not(".active").removeClass("disabled").css("pointer-events","auto")})});var n=document.getElementById("divRoleIdFirst");null!==n&&n.remove(),e($("#position").find(":selected").text(),"First",!1,"position[]")})},s=function(){null!==t&&($("#position").trigger("change"),$("#divRoleIdFirst").find('option[value="'+t[1]+'"]').prop("selected",!0),null!==n&&$.each(n,function(e,t){$("#roleId"+e).trigger("click"),$("#divRoleIdroleId"+e).find('option[value="'+t+'"]').prop("selected",!0)}))},r=function(){$("#addSkillBtn").on("click",function(){if(o<=4){o+=1;var e=0!==l?l:o,t=$('<div id="SkillInpId'+e+'" class="col-sm-8"><input class="form-control" type="text" name="additional_skill_'+e+'[]" placeholder="what is the hardest skill that you know?"></div><div id="skillDropDownContainerId'+e+'" class="col-sm-3"><select id="experienceDropDownId1" class="form-control col-sm-6" name="additional_skill_'+e+'[]"><option value="0">0-1 years</option><option value="1">1-2 years</option><option value="2">2-4 years</option><option value="3">4-6 years</option><option value="4">6-8 years</option><option value="5">8-10 years</option><option value="6">10+ years</option></select></div><btn class="close col-sm-1 deleteBtn glyphicon glyphicon-trash" id="'+e+'"><span aria-hidden="true"></span></btn>');$("#additionalSkills").append(t)}})},d=function(){$(document).on("click",".deleteBtn",function(){document.getElementById("SkillInpId"+this.id).remove(),document.getElementById("skillDropDownContainerId"+this.id).remove(),this.remove(),l=this.id,o-=1})},p=function(){if(null!==i){var e=i.slice(1);$.each(e,function(e,t){$("#addSkillBtn").trigger("click");var n=$("#additionalSkills");n.find("input:last").val(t[0]),n.find("select:last").find('option[value="'+t[1]+'"]').prop("selected",!0)})}};return{init:function(e,l,u){t=jQuery.parseJSON(e),n=jQuery.parseJSON(l),i=jQuery.parseJSON(u),a=0,o=1,c(),s(),r(),d(),p()}}},StepThree=new function(){var e,t,n,i,a=0,o=function(){$.typeahead({input:".js-typeahead-city",order:"desc",source:{data:t,minLength:2,maxItem:3}})},l=function(){$("#addCityInputBtn").on("click",function(){a<=3&&(e+=1,a+=1,$("#locationPreferencesDiv").append('<div class="typeahead__container" id="insertCityInputId'+e+'"><div class="typeahead__field"><span class="typeahead__query"><input class="js-typeahead-city" name="locations[]" type="search" placeholder="Search" autocomplete="off"</span></div></div>'),$("#delIconForCityDiv").append('<btn class="close col-sm-12 delSkillInputBtn" aria-label="Close" id="'+e+'"><span class="glyphicon glyphicon-trash" aria-hidden ="true"/></btn>'),o())})},c=function(){$(document).on("click",".delSkillInputBtn",function(){document.getElementById("insertCityInputId"+this.id).remove(),this.remove(),a-=1})},s=function(){if(console.log(),0!==i.length)$.each(i.slice(1),function(e,t){$("#addCityInputBtn").trigger("click"),$("#locationPreferencesDiv").find("input:last").val(t)});else if(null!==n){var e=n.slice(1);$.each(e,function(e,t){$("#addCityInputBtn").trigger("click"),$("#locationPreferencesDiv").find("input:last").val(t)})}},r=function(){$("#base_salaryRadio, #contract_rateRadio").on("click",function(){$("#base_salaryDiv").toggleClass("hidden"),$("#contract_rateDiv").toggleClass("hidden")})};return{init:function(a,d,p){e=0,t=a,n=jQuery.parseJSON(d),i=p,o(),l(),c(),s(),r()}}},StepFive=new function(){var e,t,n=0,i=0,a=function(){$("#educationDiv").find(".hasYear").yearselect({start:(new Date).getFullYear()-50,end:(new Date).getFullYear()}).attr("style","margin-bottom: 5%;")},o=function(){$("input[type=radio][name=importResume]").change(function(){"importResume"===this.value?($("#workExperienceDiv").attr("style","display:none"),$("#educationDiv").attr("style","display:none"),$("#importResumeDiv").attr("style","display:block")):($("#workExperienceDiv").attr("style","display:block"),$("#educationDiv").attr("style","display:block"),$("#importResumeDiv").attr("style","display:none"))})},l=function(){$("#addExperience").on("click",function(){if(n<4){var e=$('<div class="col-sm-12 lineInput"><h5> Work Experience </h5><input type="text" name="company[]" class="form-control" placeholder="Company Name"><input type="text" name="title[]" class="form-control" placeholder="Title"><div class="row"><div class="col-sm-6"><small> Start Date (M/Y)</small><input type="date" name="start_date[]" class="form-control" placeholder="Start Date (M/Y)"></div><div class="col-sm-6"><small> End Date (M/Y)</small><input type="date" name="end_date[]" class="form-control" placeholder="End Date (M/Y)"></div></div><label class="currentExperience">Current<input type="hidden" value="0" name="current[]"><input type="checkbox" value="1" name="current[]"><span class="checkmark"></span></label><button class="btn btn-block col-sm-12 experienceRemove" onclick=" $(this).closest(\'div\').remove();" style="margin-bottom: 20px"> Delete </button></div>');$("#dynamicExperience").append(e),s(),n++}}),$(document).on("click",".experienceRemove",function(){n-=1})},c=function(){$("#addEducation").on("click",function(){if($("#educationDiv").find(".hasYear").removeClass("hasYear"),i<3){var e=$('<div class="col-sm-12 lineInput"><h5>Education</h5><input type="text" name="university[]" class="form-control" placeholder="University"><input type="text" name="degree[]" class="form-control" placeholder="Degree"><small>Year</small><input class="yearselect hasYear form-control" name="degree_date[]" value="2016"><button class="btn btn-block col-sm-12" onclick=" $(this).closest(\'div\').remove();_additionalEducationCounter--" style="margin-bottom: 20px"> Delete </button></div>');$("#dynamicEducation").append(e),a(),i++}})},s=function(){$('input:checkbox[name="current[]"]').click(function(){$("input[type=date]").each(function(){$(this).removeClass("hidden")}),$(this.closest("div")).find('input[name="end_date[]"]').addClass("hidden"),$('input:checkbox[name="current[]"]').not(this).prop("checked",!1),$(this).is(":checked")?$(this).prop("checked",!0):($(this).prop("checked",!1),$(this.closest("div")).find('input[name="end_date[]"]').removeClass("hidden"))})},r=function(){if(e.hasOwnProperty("company"))for(var t=e.company.length,n=1;n<t;n++){$("#addExperience").trigger("click");var i=$("#dynamicExperience");i.find('input[name="company[]"]').last().val(e.company[n]),i.find('input[name="title[]"]').last().val(e.title[n]),i.find('input[name="start_date[]"]').last().val(e.start_date[n]),i.find('input[name="end_date[]"]').last().val(e.end_date[n]),1==e.current[n]&&i.find('input[name="current[]"]').last().trigger("click")}},d=function(){if(t.hasOwnProperty("university"))for(var e=t.university.length,n=1;n<e;n++){$("#addEducation").trigger("click");var i=$("#educationDiv");i.find('input[name="university[]"]').last().val(t.university[n]),i.find('input[name="degree[]"]').last().val(t.degree[n]),i.find("select:last").find('option[value="'+t.degree_date[n]+'"]').prop("selected",!0)}},p=function(){$("#submit_step5").on("click",function(){"createResume"===$("input[name=importResume]:checked").val()?$("form#create_resume_form").submit():$("form#import_resume_form").submit()})};return{init:function(n,i){e=JSON.parse(n),t=JSON.parse(i),a(),o(),l(),c(),s(),r(),d(),p()}}};