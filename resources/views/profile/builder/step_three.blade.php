@extends('profile.builder.master')

@section('content')
    <div class="container">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="panel panel-default col-sm-12">
            <div class="row panel-heading">Profile Builder</div>
            <div class="panel-body">
                @include('profile.builder.wizardProgress' , ['completed_steps' => $completed_steps, 'current_step' => 3])

                <form action={{ route('profile.builder.step_three_post') }} method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Employment type-->
                            <div class="form-group col-sm-12">
                                <label> What type of employment </label>
                                <div>
                                     <input type="checkbox" name="employment_type" value="Permanent">   Permanent<br />
                                     <input type="checkbox" name="employment_type" value="Contract">  Contract<br />
                                     <input type="checkbox" name="employment_type" value="Intern">  Intern<br />
                                </div>
                            </div>

                            <!--US Work Authorization-->
                            <div class="form-group col-sm-12 ">
                                <label class="card-title"> US Work Authorization
                                    <span data-toggle="tooltip"
                                          title="Applicants must be currently authorized to work in the United States on a full-time basis. In compliance with federal law, all persons hired will be required to verify identity and eligibility to work in the United States and to complete the required employment eligibility verification document form upon hire."
                                          class="glyphicon glyphicon-question-sign">
                                    </span>
                                </label>


                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn active">
                                        <input type="radio" name='work_authorization' value=1 checked
                                               @if (old('work_authorization', $work_authorization) == '1') checked @endif >
                                        <i class="fa fa-circle-o fa-2x"></i>
                                        <i class="fa fa-dot-circle-o fa-2x"></i>
                                        <span> Yes </span>
                                    </label>
                                    <label class="btn">
                                        <input type="radio" name='work_authorization' value=0
                                               @if (old('work_authorization', $work_authorization) == '0') checked @endif >
                                        <i class="fa fa-circle-o fa-2x"></i>
                                        <i class="fa fa-dot-circle-o fa-2x"></i>
                                        <span> No </span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 ">
                                <label> Require sponsorship now or future (H1-B Visa Status) </label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn active">
                                        <input type="radio" name='require_sponsorship' value=1 checked
                                                @if (old('require_sponsorship', $require_sponsorship) == '1') checked @endif>
                                        <i class="fa fa-circle-o fa-2x"></i>
                                        <i class="fa fa-dot-circle-o fa-2x"></i>
                                        <span> Yes </span>
                                    </label>
                                    <label class="btn">
                                        <input type="radio" name='require_sponsorship' value=0
                                                @if (old('require_sponsorship', $require_sponsorship) == '0') checked @endif>
                                        <i class="fa fa-circle-o fa-2x"></i>
                                        <i class="fa fa-dot-circle-o fa-2x"></i>
                                        <span> No </span>
                                    </label>
                            </div>
                            </div>
                            <!-- USDOD Clearance: -->
                            <div class="form-group col-sm-12 ">
                                <label for="usdod"> USDOD Clearance: </label>
                                <select id="usdod" name="usdod" class="form-control">
                                    <option value="0"
                                            @if (old('usdod', $usdod) == '0') selected="selected" @endif> None
                                    </option>
                                    <option value="1"
                                            @if (old('usdod', $usdod) == '1') selected="selected" @endif>
                                        Confidential
                                    </option>
                                    <option value="2"
                                            @if (old('usdod', $usdod) == '2') selected="selected" @endif>
                                        Secret
                                    </option>
                                    <option value="3"
                                            @if (old('usdod', $usdod) == '3') selected="selected" @endif> Top
                                        Secret
                                    </option>
                                    <option value="4"
                                            @if (old('usdod', $usdod) == '4') selected="selected" @endif>
                                        TS/SCI
                                    </option>
                                    <option value="5"
                                            @if (old('usdod', $usdod) == '5') selected="selected" @endif>
                                        TS/SCI
                                        with CI poly
                                    </option>
                                    <option value="6"
                                            @if (old('usdod', $usdod) == '6') selected="selected" @endif>
                                        Public
                                        Trust
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <label> Location preferences - you can add more then one city </label>
                            </div>

                            <div class="col-sm-8">
                                <div id="locationPreferencesDiv">
                                    <div class="typeahead__container">
                                        <div class="typeahead__field">
                                            <span class="typeahead__query">
                                                <input class="js-typeahead-city {{ $errors->has('locations.0') ? 'has-error' : ''}}"
                                                       name="locations[]" type="search"
                                                       placeholder="Search" autocomplete="off"
                                                       @if (isset($userLocation[0])) value="{{old('locations.0', $userLocation[0])}}"
                                                       @else value="{{old('locations.0')}}"@endif>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div id="addCityInputBtn">
                                    <span class="btn btn-primary btn-circle">+</span>
                                </div>
                            </div>

                            <div class="col-sm-4" id='delIconForCityDiv'>
                                <div class="col-sm-12 text-info"> &nbsp; &nbsp; &nbsp; &nbsp;</div>
                            </div>
                            <div class="form-group col-sm-12 ">
                                <label for="searching_status"> Where are you in your job search </label>
                                <select id="searching_status" name="searching_status" class="form-control">
                                    <option value="1"
                                            @if (old('searching_status', $searching_status) == '1') selected="selected" @endif>
                                        Currently interviewing
                                    </option>
                                    <option value="2"
                                            @if (old('searching_status', $searching_status) == '2') selected="selected" @endif>
                                        Haven't yet, but ready to start
                                    </option>
                                    <option value="3"
                                            @if (old('searching_status', $searching_status) == '3') selected="selected" @endif>
                                        Interview Stages
                                    </option>
                                    <option value="4"
                                            @if (old('searching_status', $searching_status) == '4') selected="selected" @endif>
                                        Not open to opportunities
                                    </option>
                                </select>
                            </div>

                            <div class="form-group col-sm-12 ">
                                <label> Earliest Date Available </label>
                                <input name="date_available" type="date" min="{{(new DateTime())->format('Y-m-d')}}"
                                       class="form-control {{ $errors->has('date_available') ? 'has-error' : ''}}"
                                       value="{{old('date_available', $date_available)}}">
                                @if($errors->has('date_available')) <p
                                        class="text-danger">{{$errors->first('date_available')}} </p> @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            

                             <!--Additional info textarea-->
                            <div class="form-group col-sm-12 ">
                                <label>Additional Info</label>
                                <div class="form-group">
                                    <textarea
                                            class="form-control {{ $errors->has('additional_info') ? 'has-error' : ''}}"
                                            name="additional_info" style="background-color: transparent"
                                            placeholder="401k, equity, stock purchase plan, bonus, pto, healthcare">{{old('additional_info', $additional_info)}}</textarea>
                                </div>
                                @if($errors->has('additional_info')) <p
                                        class="text-danger">{{$errors->first('additional_info')}} </p> @endif
                            </div>

                            


                        </div>

                        <div class="col-sm-6">
                            
                            </div>

                            <!--Current Compensation-->
                            <div class="form-group col-sm-12 ">
                                <label> Current Compensation </label>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn active compensation-radio" id="base_salaryRadio">
                                        <input type="radio" name='current_compensation' value="base_salary"
                                               checked
                                               @if (old('current_compensation', $current_compensation) == 'base_salary') checked @endif>
                                        <i class="fa fa-circle-o fa-2x"></i> <i class="fa fa-dot-circle-o fa-2x"></i>
                                        <span> Base Salary </span>
                                    </label>
                                    <label class="btn" id="contract_rateRadio">
                                        <input type="radio" name='current_compensation' value="contract_rate"
                                               @if (old('current_compensation', $current_compensation) == 'contract_rate') checked @endif>
                                        <i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i>
                                        <span> Contract Rate </span>
                                    </label>
                                </div>

                                <div class="input-group col-sm-7 @if (old('current_compensation', $current_compensation) == 'contract_rate') hidden @endif" id="base_salaryDiv">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input name="current_base_salary" id="base_salaryInput"
                                           type="number" step="0.5" min="0"
                                           class="form-control {{ $errors->has('current_base_salary') ? 'has-error' : ''}}"
                                           value="{{old('current_base_salary', $current_base_salary) }}"
                                           placeholder="Base salary" >
                                    @if($errors->has('current_base_salary'))
                                        <p class="text-danger">{{$errors->first('current_base_salary')}} </p>
                                    @endif
                                </div>

                                <div class="input-group col-sm-7 @if (old('current_compensation', $current_compensation) == 'base_salary') hidden @endif" id="contract_rateDiv">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input name="current_contract_rate" id="contract_rateInput"
                                           value="{{old('current_contract_rate', $current_contract_rate)}}"
                                           type="number" step="0.5" min="0"
                                           class="form-control {{ $errors->has('current_contract_rate') ? 'has-error' : ''}}"
                                           placeholder="Contract rate">
                                    @if($errors->has('current_contract_rate'))
                                        <p class="text-danger">{{$errors->first('current_contract_rate')}} </p>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group col-sm-12 ">
                                <label> Targeted Compensation
                                    <!-- <small class="text-muted">(optional)</small> -->
                                </label>

                                <div class="input-group col-sm-7">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input name="target_base_salary" type="number" step="0.5" min="0"
                                           class="form-control {{$errors->first('target_base_salary')}}"
                                           value="{{old('target_base_salary', $target_base_salary)}}"
                                           placeholder="Base Salary">
                                    @if($errors->has('target_base_salary')) <p
                                            class="text-danger">{{$errors->first('target_base_salary')}} </p> @endif
                                </div>

                                <div class="input-group col-sm-7">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                                    <input name="target_contract_rate" type="number" step="0.5" min="0"
                                           class="form-control {{$errors->first('target_contract_rate')}}"
                                           value="{{old('target_contract_rate', $target_contract_rate)}}"
                                           placeholder="Contract rate">
                                    @if($errors->has('target_contract_rate')) <p
                                            class="text-danger">{{$errors->first('target_contract_rate')}} </p> @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <a href="{{route('profile.builder.step_two')}}" class="btn btn-primary"> BACK </a>
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

        var repopulateCities = [
            @for($counter = 0; $counter<5; $counter++)
                    @if( old('locations.'. $counter) != '')
                '{!! old('locations.'. $counter) !!}',
            @endif
            @endfor
        ];

        $(function () {
            StepThree.init(
                JSON.parse({!!$cityArray!!}),
                '{!!json_encode($userLocation)!!}',
                repopulateCities
            );
        });
    </script>
@endsection