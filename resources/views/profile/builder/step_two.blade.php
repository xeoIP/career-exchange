@extends('profile.builder.master')


@section('before_scripts')
   <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/css/mdb.min.css" rel="stylesheet">-->
@endsection

@section('content')
    <div class="container">
        <div class="panel panel-default col-sm-12">
            <div class="row panel-heading">Profile Builder</div>
            <div class="panel-body">

                @include('profile.builder.wizardProgress', ['completed_steps' => $completed_steps, 'current_step' => 2])
                <form action="{{ route('profile.builder.step_two_post') }}" method="post">
                    <div class="row center-block">
                        <div class="form-group col-sm-6">
                            <label>What type of position are you looking for? </label>
                            <select id="position" name="position[]" class="form-control">
                                <option value="0"> Select one</option>
                                @foreach($positions as $position)
                                    <option value="{{$position->id}}" data-roles="{{$position->roles}}" @if ($user->position_id == $position->id) selected="selected" @endif> {{$position->name}}</option>
                                @endforeach
                            </select>

                        </div>
                            <label>What is your area of expertise? (Choose up to three)</label>
                        <!-- js generated role btn-s for Positions-->
                        <div id="roles" class="btn-group col-sm-6"></div>
                        
                        
                    </div>

                    <div class="row center-block">
                    <label>How much experience do you have in each?</label>
                        <!-- js generated skills for roles (onchange & onBtnClick) -->
                        <div id="experience" class="col-sm-12"></div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0">Rank your top 5 skills in descending order and years of experience: </label>
                                <p>
                                    <small class="text-muted"> This would include technologies, languages, frameworks.
                                    </small>
                                </p>
                            </div>

                            @for ($skillCount = 1; $skillCount <= 5; $skillCount++)
                                <div class="row-fluid">
                                    <div class="col-sm-9">
                                        <input type="text" name="skill_{{$skillCount}}[]" class="form-control" placeholder="#{{$skillCount}} Skill"
                                               @if (isset($skills[$skillCount-1])) value="{{$skills[$skillCount-1][0]}}" @endif>
                                    </div>

                                    <div class="col-sm-3">
                                        <select id="experienceDropDownId1" name="skill_{{$skillCount}}[]" class="form-control">
                                            <option value="0" @if (isset($skills[$skillCount-1]) && $skills[$skillCount-1][1] == 0) selected="selected" @endif> 0-1 years</option>
                                            <option value="1" @if (isset($skills[$skillCount-1]) && $skills[$skillCount-1][1] == 1) selected="selected" @endif> 1-2 years</option>
                                            <option value="2" @if (isset($skills[$skillCount-1]) && $skills[$skillCount-1][1] == 2) selected="selected" @endif> 2-4 years</option>
                                            <option value="3" @if (isset($skills[$skillCount-1]) && $skills[$skillCount-1][1] == 3) selected="selected" @endif> 4-6 years</option>
                                            <option value="4" @if (isset($skills[$skillCount-1]) && $skills[$skillCount-1][1] == 4) selected="selected" @endif> 6-8 years</option>
                                            <option value="5" @if (isset($skills[$skillCount-1]) && $skills[$skillCount-1][1] == 5) selected="selected" @endif> 8-10 years</option>
                                            <option value="6" @if (isset($skills[$skillCount-1]) && $skills[$skillCount-1][1] == 6) selected="selected" @endif> 10+ years</option>
                                        </select>
                                    </div>

                                </div>
                            @endfor

                        </div>
                        <div class="col-sm-6" id="additionalSkills">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0"> Additional skills:</label>
                                <p>
                                    <small class="text-muted"> Choose up to three.</small>
                                </p>
                            </div>

                            <div class="col-sm-8">
                                <input type="text" name="additional_skill_1[]"
                                       class="form-control"
                                       placeholder="Add..."
                                       @if (isset($additionalSkills[0])) value="{{$additionalSkills[0][0]}}" @endif>
                            </div>

                            <div class="col-sm-3">
                                <select id="experienceDropDownId1" name="additional_skill_1[]"  class="experienceDropDown form-control">
                                    <option value="0" @if (isset($additionalSkills[0]) && $additionalSkills[0][1] == 0) selected="selected" @endif> 0-1 years</option>
                                    <option value="1" @if (isset($additionalSkills[0]) && $additionalSkills[0][1] == 1) selected="selected" @endif> 1-2 years</option>
                                    <option value="2" @if (isset($additionalSkills[0]) && $additionalSkills[0][1] == 2) selected="selected" @endif> 2-4 years</option>
                                    <option value="3" @if (isset($additionalSkills[0]) && $additionalSkills[0][1] == 3) selected="selected" @endif> 4-6 years</option>
                                    <option value="4" @if (isset($additionalSkills[0]) && $additionalSkills[0][1] == 4) selected="selected" @endif> 6-8 years</option>
                                    <option value="5" @if (isset($additionalSkills[0]) && $additionalSkills[0][1] == 5) selected="selected" @endif> 8-10 years</option>
                                    <option value="6" @if (isset($additionalSkills[0]) && $additionalSkills[0][1] == 6) selected="selected" @endif> 10+ years</option>
                                </select>
                            </div>

                        </div>

                        <div class="col-sm-6" id="addSkillBtn">
                            <div class="col-sm-8">
                                <span class="btn btn-primary btn-circle">+</span>
                            </div>
                        </div>
                    </div>

                    <br><br>

                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <a href="{{route('profile.builder.step_one')}}" class="btn btn-primary"> BACK </a>
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

@section('after_scripts')
    <script type="text/javascript">
            $(function () {
                StepTwo.init(
                    '{!!json_encode($position->id)!!}',
                    '{!!json_encode($roles)!!}',
                    '{!!json_encode($additionalSkills)!!}'
                );
            });

    </script>
@endsection


