@extends('layouts.master')

@section('content')
    <!-- Titlebar
================================================== -->
    <div id="titlebar" class="resume">
        <div class="container">
            <div class="ten columns">
                <div class="resume-titlebar">
                    @if (!empty($gravatar))
                        <img class="userImg" src="{{ $gravatar }}" alt="user">&nbsp;
                    @else
                        <img class="userImg" src="{{ url('images/user.jpg') }}" alt="user">
                    @endif
                    <div class="resumes-list-content">
                        <h4>{{ $user->name }} <span>{{$profileData['position']}}</span></h4>
                        <span>{{$profileData['additionalInfo']}}</span>
                        <span class="icons"><i class="fa fa-map-marker"></i>{{$profileData['user_city']}}, {{  $profileData['user_city_code'] }}</span>
                        <span class="icons"><a href="{{$profileData['website'] }}"><i
                                        class="fa fa-link"></i> Website</a></span>
                        <span class="icons"><a href="mailto:{{$profileData['email']}}"><i
                                        class="fa fa-envelope"></i> {{$profileData['email']}} </a></span>
                        <div class="skills">
                            @foreach($profileData['skills'] as $skill)
                                <span>{{$skill->name}}</span>
                            @endforeach

                        </div>
                        <div class="clearfix"></div>

                    </div>
                </div>
            </div>

            <div class="six columns">
                <div class="two-buttons">

                    @if($profileData['preferences']->work_authorization == 1)
                    Authorized <br> to work in US
                    <div class="rating five-stars">
                        <div class="star-rating"></div>
                        <div class="star-sm"></div>
                    </div>
                    @endif

                    <a href="#small-dialog" class="popup-with-zoom-anim button"><i class="fa fa-envelope"></i> Send
                        Message</a>

                    <div id="small-dialog" class="zoom-anim-dialog mfp-hide apply-popup">
                        <div class="small-dialog-headline">
                            <h2>Send Message to John Doe</h2>
                        </div>

                        <div class="small-dialog-content">
                            <form action="#" method="get">
                                <input type="text" placeholder="Full Name" value=""/>
                                <input type="text" placeholder="Email Address" value=""/>
                                <textarea placeholder="Message"></textarea>

                                <button class="send">Send Application</button>
                            </form>
                        </div>

                    </div>
                    <a href="#" class="button dark"><i class="fa fa-star"></i> Bookmark This Resume</a>


                </div>
            </div>

        </div>
    </div>
    <!-- Content
    ================================================== -->
    <div class="container">
        <!-- Recent Jobs -->
        <div class="eight columns">
            <div class="padding-right">

                <h3 class="margin-bottom-15">About Me</h3>
                <p><i class="fa fa-cog"></i> <strong>Interested
                        in @foreach($profileData['position_roles'] as $position_role) @if($loop->last)
                            and @endif {{$position_role->name}} ,  @endforeach positions</strong></p>


                @foreach($profileData['education'] as $education)

                    <strong>{{$education->university}}</strong> —  {{$education->degree_date}}

                    <ul class="list-1">
                        <li>{{$education->degree}}</li>
                    </ul>
                @endforeach

            </div>
            <hr class="margin-top-20 margin-bottom-20">

            <strong> Earliest Date Available </strong>
            — {{$profileData['preferences']->date_available->format('m/d/Y')}}

        </div>

        <!-- Widgets -->
        <div class="eight columns">

            <h3 class="margin-bottom-20">Work Experience</h3>

            <!-- Resume Table -->
            <dl class="resume-table">

                @foreach ($profileData['experience'] as $experience )

                    <dt>
                        <strong>{{$experience->company}}</strong>
                        <strong>{{$experience->title}}</strong>
                        <small class="date">{{$experience->start_date->format('m/d/Y')}} — present( {{$experience->end_date->diff($experience->start_date)->format('%y Years, %m Months')}} ) </small>
                    </dt>


                @endforeach

            </dl>

        </div>

    </div>
    <!-- /.main-container -->
@endsection

@section('after_styles')
    <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    <style>
        .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
            box-shadow: 0 0 5px 0 #666666;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    @if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js'))
        <script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.config('app.locale').'.js') }}"
                type="text/javascript"></script>
    @endif

    <script>
        /* initialize with defaults (resume) */
        $('#filename').fileinput(
            {
                language: '{{ config('app.locale') }}',
                showPreview: false,
                allowedFileExtensions: {!! getUploadFileTypes('file', true) !!},
                browseLabel: '{!! t("Browse") !!}',
                showUpload: false,
                showRemove: false,
                maxFileSize: {{ (int)config('settings.upload_max_file_size', 1000) }}
            });
    </script>
    <script>
        var userType = '<?php echo old('user_type', $user->user_type_id); ?>';

        $(document).ready(function () {
            /* Set user type */
            setUserType(userType);
            $('#userType').change(function () {
                setUserType($(this).val());
            });
        });
    </script>
@endsection
