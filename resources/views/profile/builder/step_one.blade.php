@extends('profile.builder.master')

@section('content')
    <div class="container">

        <div class="panel panel-default col-sm-12">
            <div class="row panel-heading">Profile Builder</div>
            <div class="panel-body">


            @include('profile.builder.wizardProgress', ['completed_steps' => $completed_steps, 'current_step' => 1])

                <form method="post" id="step1FormId" name="step1Form" action={{ route('profile.builder.step_one_post') }} enctype="multipart/form-data">
                    <div class="col-sm-6 lineInput">
                        <label>First Name:</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $first_name) }}" class="form-control {{ $errors->has('first_name') ? 'has-error' : '' }}">
                        @if($errors->has('first_name')) <p class="text-danger">{{$errors->first('first_name')}} </p> @endif

                        <label>Last Name:</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $last_name) }}" class="form-control {{ $errors->has('last_name') ? 'has-error' : '' }}">
                        @if($errors->has('last_name')) <p class="text-danger">{{$errors->first('last_name')}} </p> @endif

                        <label>Email:</label>
                        <input type="text" name="email" value="{{ old('email', $email) }}" class="form-control {{ $errors->has('email') ? 'has-error' : '' }}">
                        @if($errors->has('email')) <p class="text-danger">{{$errors->first('email')}} </p> @endif

                        <label>Phone Number:</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $phone) }}" class="form-control {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                        @if($errors->has('phone_number')) <p class="text-danger">{{$errors->first('phone_number')}} </p> @endif

                        <!-- <label>Social Security:</label>
                        <input type="text" name="social_security" value="{{ old('social_security', $social_security) }}"  class="form-control {{ $errors->has('social_security') ? 'has-error' : ''}}">
                        @if($errors->has('social_security')) <p class="text-danger">{{$errors->first('social_security')}} </p> @endif -->

                    </div>

                    <div class="row center-block">
                        <!-- importPicture-->
                        <div class="col-sm-6" id="importResumeDiv">
                            <label> Photo (optional):</label>
                            <div id="import-photo" class="alert alert-info">
                                <label class="btn btn-primary" for="myPhotoSelector">
                                    <input id="myPhotoSelector" name="photoInput" type="file"
                                           style="display:none"
                                           onchange="$('#upload-file-info').html(this.files[0].name);">
                                    <span class="glyphicon glyphicon-folder-open" style="margin-bottom: 1%; margin-top: 10%; padding-right: 1%">
                                        <p>Browse</p>
                                    </span>
                                </label>

                                <span class='label label-info' id="upload-file-info" style="font-size: 20px;">@if(session('client_photo_name')!=null) {{session('client_photo_name')}}@endif</span>
                                <img id="profilePicturePreview" @if($profile_picture!= null ) src="{{$profile_picture}}" @endif />
                            </div>
                        </div>
                    </div>
                    <div class="row center-block">
                        <div class="form-group col-sm-6">
                            <label>Country:</label>
                            <select id="country" name="country" class="form-control" disabled>
                                <option value="USA" selected> USA</option>
                            </select>
                        </div>

                        <div class="form-group col-sm-6">
                            <label>City:</label>
                            <div class="typeahead__container">
                                <div class="typeahead__field">
                                    <span class="typeahead__query">
                                        <input class="js-typeahead-city {{ $errors->has('city') ? 'has-error' : ''}}" name="city" type="search"
                                               value="{{  old('city', $city) }}" placeholder="Search"
                                               autocomplete="off">
                                    </span>
                                </div>
                            </div>
                            @if($errors->has('city')) <p class="text-danger">{{$errors->first('city')}} </p> @endif

                        </div>

                    </div>

                    <div class="row">
                        <input type="hidden" name="id" value="{{ Auth::user()->id }}"/>
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary"> Next step</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
<script type="text/javascript">
    $(function () {
        StepOne.init(JSON.parse({!!$cityArray!!}));
    });
</script>
@endsection