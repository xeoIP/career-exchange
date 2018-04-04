@extends('profile.builder.master')


@section('content')
    <div class="container">



        <div class="panel panel-default col-sm-12">
            <div class="row panel-heading">Profile Builder</div>
            <div class="panel-body">
                @include('profile.builder.wizardProgress' , ['completed_steps' => $completed_steps, 'current_step' => 4])

                <form method="post" action="{{route('profile.builder.step_four_post')}}">
                    <div class="col-sm-12 lineInput">
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" name="linkedIn" class="form-control link-input {{ $errors->has('linkedIn') ? 'has-error' : ''}}" placeholder="LinkedIn Profile" value="{{old('linkedIn', $linkedIn)}}">
                                @if($errors->has('linkedIn')) <p class="text-danger">{{$errors->first('linkedIn')}} </p> @endif
                            </div>

                            <div class="col-sm-6">
                                <input type="text" name="github" class="form-control link-input {{ $errors->has('github') ? 'has-error' : ''}}" placeholder="GitHub" value="{{old('github', $github)}}">
                                @if($errors->has('github')) <p class="text-danger">{{$errors->first('github')}} </p> @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" name="stackOverflow" class="form-control link-input {{ $errors->has('stackOverflow') ? 'has-error' : ''}}" placeholder="Stack Overflow URL" value="{{old('stackOverflow', $stackOverflow)}}">
                                @if($errors->has('stackOverflow')) <p class="text-danger">{{$errors->first('stackOverflow')}} </p> @endif
                            </div>

                            <div class="col-sm-6">
                                <input type="text" name="website" class="form-control link-input {{ $errors->has('website') ? 'has-error' : ''}}" placeholder="Personal Website" value="{{old('website', $website)}}">
                                @if($errors->has('website')) <p class="text-danger">{{$errors->first('website')}} </p> @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <input type="text" name="resume" class="form-control link-input {{ $errors->has('resume') ? 'has-error' : ''}}" placeholder="Resume" value="{{old('resume', $resume)}}">
                                @if($errors->has('resume')) <p class="text-danger">{{$errors->first('resume')}} </p> @endif
                            </div>

                            <div class="col-sm-6">
                                <input type="text" name="twitter" class="form-control link-input {{ $errors->has('twitter') ? 'has-error' : ''}}" placeholder="Twitter" value="{{old('twitter', $twitter)}}">
                                @if($errors->has('twitter')) <p class="text-danger">{{$errors->first('twitter')}} </p> @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <a href="{{route('profile.builder.step_three')}}" class="btn btn-primary"> BACK </a>
                        </div>

                        <div class="col-sm-6 text-right">
                            <button type="submit" class="btn btn-primary"> Next step</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
