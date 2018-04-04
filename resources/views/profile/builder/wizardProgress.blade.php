
<!--main-->
<div class="row bs-wizard" style="border-bottom:0;">

    <div id='step_one' class="col-xs-2 col-xs-offset-1  bs-wizard-step  @if($completed_steps > 0 ) complete @elseif($completed_steps==0) active @else disabled @endif  ">
        <div class="text-center bs-wizard-stepnum @if($current_step == 1) info-text @endif">Personal info</div>
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
        <a href="{{route('profile.builder.step_one')}}" class="bs-wizard-dot"></a>
    </div>

    <div id='step_two' class="col-xs-2 bs-wizard-step  @if($completed_steps > 1 ) complete @elseif($completed_steps==1) active @else disabled @endif  "><!-- complete -->
        <div class="text-center bs-wizard-stepnum @if($current_step == 2) info-text @endif"> Role and Skills </div>
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
        <a href="{{route('profile.builder.step_two')}}" class="bs-wizard-dot"></a>
    </div>

    <div id='step_three' class="col-xs-2 bs-wizard-step  @if($completed_steps > 2 ) complete @elseif($completed_steps==2) active @else disabled @endif  "><!-- complete -->
        <div class="text-center bs-wizard-stepnum @if($current_step == 3) info-text @endif">Job Preferences</div>
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
        <a href="{{route('profile.builder.step_three')}}" class="bs-wizard-dot"></a>
    </div>

    <div id='step_for' class="col-xs-2 bs-wizard-step @if($completed_steps > 3 ) complete @elseif($completed_steps==3) active @else disabled @endif  "><!-- active -->
        <div class="text-center bs-wizard-stepnum @if($current_step == 4) info-text @endif">Resumes and Links</div>
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
        <a href="{{route('profile.builder.step_four')}}" class="bs-wizard-dot"></a>
    </div>

    <div id='step_five' class="col-xs-2 bs-wizard-step  @if($completed_steps > 4 ) complete @elseif($completed_steps==4) active @else disabled @endif  "><!-- active -->
        <div class="text-center bs-wizard-stepnum @if($current_step == 5) info-text @endif">Work Experience</div>
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
        <a href="{{route('profile.builder.step_five')}}" class="bs-wizard-dot"></a>
    </div>
</div>



<style>

    /*Form Wizard*/
    .bs-wizard {
        border-bottom: solid 1px #e0e0e0;
        padding: 0 0 10px 0;
    }

    .bs-wizard > .bs-wizard-step {
        padding: 0;
        position: relative;
    }

    .bs-wizard > .bs-wizard-step + .bs-wizard-step {
    }

    .bs-wizard > .bs-wizard-step .bs-wizard-stepnum {
        color: #595959;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .bs-wizard > .bs-wizard-step .bs-wizard-info {
        color: #999;
        font-size: 14px;
    }

    .bs-wizard > .bs-wizard-step > .bs-wizard-dot {
        position: absolute;
        width: 30px;
        height: 30px;
        display: block;
        background: rgba(38, 174, 97, 0.32);
        top: 45px;
        left: 50%;
        margin-top: -15px;
        margin-left: -15px;
        border-radius: 50%;
    }

    .bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {
        content: ' ';
        width: 14px;
        height: 14px;
        background: rgb(38, 174, 97);
        border-radius: 50px;
        position: absolute;
        top: 8px;
        left: 8px;
    }

    .bs-wizard > .bs-wizard-step > .progress {
        position: relative;
        border-radius: 0px;
        height: 8px;
        box-shadow: none;
        margin: 15px 0;
    }

    /**/
    .bs-wizard > .bs-wizard-step > .progress > .progress-bar{
        width: 0px ;
        box-shadow: none;
        background: rgba(38, 174, 97, 0.32);
        display: block !important;
    }

    .bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {
        width: 100%;
    }

    .bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {
        width: 50%;
    }

    .bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {
        width: 0%;
    }

    .bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {
        width: 100%;
    }

    .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {
        background-color: #f5f5f5;
    }

    .bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {
        opacity: 0;
    }

    .bs-wizard > .bs-wizard-step:first-child > .progress {
        left: 50%;
        width: 50%;
    }

    .bs-wizard > .bs-wizard-step:last-child > .progress {
        width: 50%;
    }

    .bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot {
        pointer-events: none;
    }

    .bs-wizard .info-text {
        text-shadow: 2px 2px rgba(38, 174, 97, 0.32);;
        font-weight: bold;
    }

    /*END Form Wizard*/
</style>