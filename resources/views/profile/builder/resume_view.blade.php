@extends('layouts.master')

@section('content')

    <!-- Titlebar
================================================== -->
    <div id="titlebar" class="resume">
        <div class="container">
            <div class="ten columns">
                <div class="resume-titlebar">
                    @if (!empty($gravatar))
                        <img class="userImg" src="{{ $profile_picture != null ? $profile_picture : $gravatar }}"
                             alt="user">&nbsp;
                    @else
                        <img class="userImg" src="{{ url('images/user.jpg') }}" alt="user">
                    @endif
                    <div class="resumes-list-content">
                        <h4>{{ $user->name }} <span>{{$position!= null ? $position : ''}}</span></h4>
                        <span>{{$additionalInfo != null ? $additionalInfo : ''}}</span>
                        <span class="icons"><i class="fa fa-map-marker"></i>{{$user_city}}
                            , {{  $user_city_code }}</span>
                        <span class="icons"><a href="{{$website != null ? $website : '#'}}"><i
                                        class="fa fa-link"></i> Website</a></span>
                        <span class="icons"><a href="mailto:{{$email}}"><i
                                        class="fa fa-envelope"></i> {{$email}} </a></span>
                        <div class="skills">
                            @if($skills != null)
                                @foreach($skills as $skill)
                                    <span>{{$skill->name}}</span>
                                @endforeach
                            @endif

                        </div>
                        <div class="clearfix"></div>

                    </div>
                </div>
            </div>

            <div class="six columns">
                <div class="two-buttons">
                    @if(isset($preferences->work_authorization))
                        @if($preferences->work_authorization == 1)
                            Authorized <br> to work in US
                            <div class="rating five-stars">
                                <div class="star-rating"></div>
                                <div class="star-sm"></div>
                            </div>
                        @endif
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
                    <a href="{{route('profile.builder.step_one')}}" class="button dark"><i class="fa fa-star"></i> Make some changes to resume</a>


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
                        in @if(!empty($position_roles)) @foreach($position_roles as $position_role) @if($loop->last)
                            and @endif {{$position_role->name}} ,  @endforeach @endif positions</strong></p>

                @if(!empty($educations))
                    @foreach($educations as $education)

                        <strong>{{$education->university}}</strong> —  {{$education->degree_date}}

                        <ul class="list-1">
                            <li>{{$education->degree}}</li>
                        </ul>
                    @endforeach
                @endif

            </div>
            <hr class="margin-top-20 margin-bottom-20">

            <div style="padding-bottom: 2%">
                <strong> Earliest Date Available </strong>
                — {{$preferences != null ? $preferences->date_available->format('m/d/Y') : ''}}
            </div>
        </div>

        <!-- Widgets -->
        <div class="eight columns">

            <h3 class="margin-bottom-20">Work Experience</h3>

            <!-- Resume Table -->
            <dl class="resume-table">

                @if($experiences != null)
                    @foreach ($experiences as $experience )

                        <dt>
                            <strong>{{$experience->company}}</strong>
                            <strong>{{$experience->title}}</strong>
                            <small class="date">{{$experience->start_date != null ? $experience->start_date->format('m/d/Y') : ''}}
                                —
                                 {{$experience->end_date != null ? 'present(' . $experience->end_date->diff($experience->start_date)->format('%y Years, %m Months'). ')' : ''}}
                                 {{$experience->current == 1 ? 'currently working for this company':''}}
                            </small>
                        </dt>
                    @endforeach
                @endif

            </dl>

        </div>

    </div>
    <!-- /.main-container -->
@endsection

@section('after_styles')
    <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">

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
