@extends('profile.builder.master')


@section('content')
    <div class="container">

        <div class="panel panel-default col-sm-12">
            <div class="row panel-heading">Profile Builder</div>
            <div class="panel-body">
                @include('profile.builder.wizardProgress' , ['completed_steps' => $completed_steps, 'current_step' => 5])

                <div class="row">
                    <div class="row">
                        <div class="btn-group col-sm-12" data-toggle="buttons">
                            <label class="btn active" id="createResumeLabel">
                                <input type="radio" name='importResume' value="createResume" checked>
                                <i class="fa fa-circle-o fa-2x"></i>
                                <i class="fa fa-dot-circle-o fa-2x"></i>
                                <span> Fill in Manually </span>
                            </label>
                            <label class="btn" id="importResumeLabel">
                                <input type="radio" name='importResume' value="importResume">
                                <i class="fa fa-circle-o fa-2x"></i>
                                <i class="fa fa-dot-circle-o fa-2x"></i>
                                <span> Import Resume </span>
                            </label>
                        </div>
                    </div>

                    <form action="{{route('profile.builder.store')}}" id="create_resume_form" method="post">
                        <div class="col-sm-6" id="workExperienceDiv">
                            <div class="col-sm-12 lineInput">
                                <h5> Work Experience </h5>
                                <input type="text" name="company[]" class="form-control" placeholder="Company Name" value="{{old('company[]', isset($experience['company'][0])?$experience['company'][0]:'' )}}">

                                <input type="text" name="title[]" class="form-control" placeholder="Title" value="{{old('title[]', isset($experience['title'][0])?$experience['title'][0]:'')}}">

                                <div class="row">
                                    <div class="col-sm-6">
                                        <small> Start Date</small>
                                        <input type="date" name="start_date[]" id="datefield1" class="form-control"
                                               placeholder="End Date (M/Y)" max="{{(new DateTime())->format('Y-m-d')}}" value="{{old('start_date[]', isset($experience['start_date'][0])?$experience['start_date'][0]:'')}}">

                                    </div>

                                    <div class="col-sm-6">
                                        <small> End Date</small>
                                        <input type="date" name="end_date[]" id="datefield" class="form-control @if(isset($experience['current'][0])) {{$experience['current'][0] == 1 ? 'hidden' : ''}}@endif"
                                               placeholder="End Date (M/Y)" max="{{(new DateTime())->format('Y-m-d')}}" value="{{old('end_date[]', isset($experience['end_date'][0])?$experience['end_date'][0]:'')}}">

                                    </div>
                                </div>

                                <!-- Current -->
                                <label class="currentExperience">Current
                                    <input type="checkbox" value="1" name="current[]" @if(isset($experience['current'][0])) {{$experience['current'][0] == 1 ? 'checked' : ''}}@endif>
                                    <span class="checkmark"></span>
                                </label>

                            </div>

                            <div id="dynamicExperience"></div>

                            <div class="col-sm-6" id="addExperience">
                                <div class="col-sm-8">
                                    <span class="btn btn-primary btn-circle">+</span>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-6" id="educationDiv">
                            <div class="col-sm-12 lineInput">
                                <h5>Education</h5>
                                <input type="text" name="university[]" class="form-control" placeholder="University"
                                       value="{{old('university[]', isset($education['university'][0])?$education['university'][0]:'')}}">

                                <input type="text" name="degree[]" class="form-control" placeholder="Degree"
                                       value="{{old('degree[]', isset($education['degree'][0])?$education['degree'][0]:'')}}">

                                <small>Year</small>
                                <input class="yearselect hasYear form-control" name="degree_date[]"
                                       value="{{old('degree_date[]', isset($education['degree_date'][0])?$education['degree_date'][0]:'')}}">
                            </div>

                            <div id="dynamicEducation"></div>

                            <div class="col-sm-6" id="addEducation">
                                <div class="col-sm-8">
                                    <span class="btn btn-primary btn-circle">+</span>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="type"
                               value="{{\App\Http\Requests\ProfileStoreRequest::FILL_FORM_TYPE}}">
                    </form>

                    <form action="{{route('profile.builder.store')}}" id="import_resume_form" method="post"
                          enctype="multipart/form-data">
                        <div class="col-sm-6" id="importResumeDiv" style="display: none">
                            <div class="alert alert-info">
                                <label class="btn btn-primary" for="my-file-selector">
                                    <input id="my-file-selector" name="resume" type="file" style="display:none"
                                           onchange="$('#upload-file-info').html(this.files[0].name)">
                                    <span class="glyphicon glyphicon-folder-open"
                                          style="margin-bottom: 1%; margin-top: 10%; padding-right: 1%">
                                        <p>Browse</p>
                                    </span>
                                </label>

                                <span class='label label-info' id="upload-file-info" style="font-size: 20px;"></span>
                            </div>
                        </div>
                        <input type="hidden" name="type"
                               value="{{\App\Http\Requests\ProfileStoreRequest::IMPORT_FORM_TYPE}}">
                    </form>
                </div>

                <div class="row">
                    <div class="col-sm-6 text-left">
                        <a href="{{route('profile.builder.step_four')}}" class="btn btn-primary"> BACK </a>
                    </div>

                    <div class="col-sm-6 text-right">
                        <button class="btn btn-primary" id="submit_step5"> Next step</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>


        /*
                var myRadios = document.getElementsByName('current[]');
                var setCheck;

                for (var counter = 0; counter < myRadios.length; counter++) {

                    myRadios[counter].onclick = function () {
                        if (setCheck != this) {
                            setCheck = this;
                        } else {
                            this.checked = false;
                            setCheck = null;
                        }
                    };

                }*/

    </script>

@endsection

@section('after_scripts')
    <script type="text/javascript">
        $(function () {
            StepFive.init(
                '{!!json_encode($experience)!!}',
                '{!!json_encode($education)!!}'
            );
        });
    </script>

@endsection


<style>
    /* The container */
    .currentExperience {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default radio button */
    .currentExperience input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom radio button */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
        border-radius: 0%;
    }

    /* On mouse-over, add a grey background color */
    .currentExperience:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the radio button is checked, add a blue background */
    .currentExperience input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the indicator (the dot/circle - hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the indicator (dot/circle) when checked */
    .currentExperience input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the indicator (dot/circle) */
    .currentExperience .checkmark:after {
        top: 9px;
        left: 9px;
        width: 8px;
        height: 8px;
        border-radius: 0%;
        background: white;
    }

    .dateLimiter {
        position: relative;
        z-index: 100;
    }
</style>




