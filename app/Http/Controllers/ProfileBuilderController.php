<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileStepThreeRequest;
use App\Http\Requests\ProfileStepFourRequest;
use App\Http\Requests\ProfileStepOneRequest;
use App\Http\Requests\ProfileStepTwoRequest;
use App\Http\Requests\ProfileStoreRequest;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPreference;
use App\Models\UserExperience;
use App\Models\UserEducation;
use App\Models\UserNetwork;
use App\Models\UserSkill;
use App\Models\Position;
use App\Models\User;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;


class ProfileBuilderController extends FrontController
{
    /**
     * @return mixed
     */
    public function stepOne()
    {
        $stepOneData = [];

        /*prepare data */
        $user = Auth::user();
        $splitName = $this->splitName($user->name);
        $cities = City::all()->pluck('name')->toJson();

        $stepOneData['first_name'] = $splitName['first'];
        $stepOneData['last_name'] = $splitName['last'];
        $stepOneData['email'] = $user->email;
        $stepOneData['phone'] = $user->phone;
        $stepOneData['social_security'] = $user->social_security;
        $stepOneData['city'] = isset($user->userCity->name) ? $user->userCity->name : null;
        $stepOneData['cityArray'] = json_encode($cities);
        $stepOneData['profile_picture'] = $user->profile_picture;
        $stepOneData['completed_steps'] = $user->profile_builder_step;


        return view('profile.builder.step_one', $stepOneData);
    }

    private function splitName($name)
    {
        $nameInParts = [];
        $splitName = explode(' ', $name);
        $nameParts = count($splitName);
        $nameInParts['first'] = $splitName[0];
        $nameInParts['last'] = $nameParts > 1 ? $splitName[$nameParts - 1] : ''; // If last name doesn't exist, make it empty

        return $nameInParts;
    }

    /**
     * @param ProfileStepOneRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function stepOnePost(ProfileStepOneRequest $request)
    {
        $user = Auth::user();
        $userCity = City::where('name', '=', $request->get('city'))->first();

        User::where('id', '=', Auth::user()->id)
            ->update([
                'name'                  => $request->get('first_name') . ' ' .$request->get('last_name'),
                'email'                 => $request->get('email'),
                'phone'                 => $request->get('phone_number'),
                'social_security'       => $request->get('social_security'),
                'city'                  => $userCity instanceof City ? $userCity->id : null
            ]);


        if ($request->hasFile('photoInput')) {



            /*remove if already uploaded */
            if ($user->profile_picture != null) {
                try{
                    @File::delete(public_path() . $user->profile_picture);
                }catch (\Exception $e){
                    report($e);
                }
            }

            try{

                $image = $request->file('photoInput');
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $publicFolderPath = 'images/profile_pictures/';

                //make name
                $newFilename = str_random() . "." . $originalName . "." . $image->guessExtension();

                //resize
                $imageResize = Image::make($image->getRealPath());
                $imageResize->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                //save to folder
                $imageResize->save(public_path($publicFolderPath) . $newFilename);

                //save to db
                $user->update(['profile_picture' => '/'. $publicFolderPath . $newFilename]);
                session(['client_photo_name'=> $image->getClientOriginalName()]);

            }catch (\Exception $exception){
                return redirect(route('profile.builder.step_two',['file_error' => 'there was a problem with your file input, try uploading photo again']));
            };

        }


        if($user->profile_builder_step == 0){
            $user->update(['profile_builder_step' => 1]);
        }

        return redirect(route('profile.builder.step_two'));
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stepTwo()
    {
        $user = Auth::user();

        $completed_steps = $user->profile_builder_step;

        if($user->profile_builder_step < $completed_steps)
        {
            return redirect('/profile/builder/' . ($user->profile_builder_step + 1) );
        };

        $skills = [];
        $additionalSkills =[];
        $skillsFromDb= $user->skills;
        $roles = [];

        $foundSkillCount = 0;

        for ($skillCount = 0; $skillCount <= 9; $skillCount++)
        {

            if(isset($skillsFromDb[$skillCount]->name) && $skillsFromDb[$skillCount]->is_additional !=1 )
            {
                $skills[$skillCount][] = $skillsFromDb[$skillCount]->name;
                $skills[$skillCount][] = $skillsFromDb[$skillCount]->experience;
                $foundSkillCount++;
            }

        }

        for ($skillCount = 0; $skillCount <= 9; $skillCount++)
        {
            if(isset($skillsFromDb[$skillCount]->name) && $skillsFromDb[$skillCount]->is_additional == 1 )
            {
                $additionalSkills[$skillCount-$foundSkillCount][] = $skillsFromDb[$skillCount]->name;
                $additionalSkills[$skillCount-$foundSkillCount][] = $skillsFromDb[$skillCount]->experience;
            }
        }

        foreach ($user->positionRoles as $role)
        {
            $roles[$role->pivot->position_role_id] = $role->pivot->rating;
        }


        return view('profile.builder.step_two', [
            'positions'          => Position::all(),
            'user'               => $user,
            'skills'             => $skills,
            'additionalSkills'   => $additionalSkills,
            'roles'              => $roles,
            'completed_steps'    => $completed_steps
        ]);
    }

    /**
     * @param ProfileStepTwoRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function stepTwoPost(ProfileStepTwoRequest $request)
    {

    $user = Auth::user();

        $stepTwoData = $request->getPrepared();

        $user->update([
            'position_id'           => isset($stepTwoData['position'][0]) ? $stepTwoData['position'][0] : null,
            'position_experience'   => isset($stepTwoData['position'][1]) ? $stepTwoData['position'][1] : null,
        ]);


        $roleIds = [];
        foreach($stepTwoData['role_experience'] as $role => $experience)
        {
            $roleIds[$role] = ['rating' => $experience];
        }
        $user->positionRoles()->sync($roleIds);



        $user->skills()->delete();

        foreach ($stepTwoData['skills'] as $skill) {
            UserSkill::create([
                'user_id'       => $user->id,
                'name'          => isset($skill[0]) ? $skill[0] : '',
                'experience'    => isset($skill[1]) ? $skill[1] : null,
            ]);
        }

        foreach ($stepTwoData['additionalSkills'] as $additionalSkill) {
            UserSkill::create([
                'user_id'       => $user->id,
                'name'          => isset($additionalSkill[0]) ? $additionalSkill[0] : '',
                'experience'    => isset($additionalSkill[1]) ? $additionalSkill[1] : null,
                'is_additional' => true,
            ]);
        }

        if($user->profile_builder_step == 1){
            $user->update(['profile_builder_step' => 2]);
        }

        return redirect(route('profile.builder.step_three'));
    }



    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stepThree()
    {
        $user = Auth::user();

        $completed_steps = $user->profile_builder_step;
        if($completed_steps < 2)
        {
            return redirect('/profile/builder/' . ($user->profile_builder_step + 1));
        };


        $userLocation = [];

        foreach ($user->cities as $dbLocation)
        {
            $userLocation[] = $dbLocation->name;
        }

        $cities = City::all()->pluck('name')->toJson();

        $date_available = isset($user->preferences->date_available) ? Carbon::parse( $user->preferences->date_available )->format('Y-m-d') : 0-0-0;

        return view('profile.builder.step_three', [
            'cityArray'             => json_encode($cities),
            'userLocation'          => $userLocation,
            'employment_type'       => isset($user->preferences->employment_type) ? $user->preferences->employment_type : 'Permanent' ,
            'work_authorization'    => isset($user->preferences->work_authorization) ? $user->preferences->work_authorization : 1,
            'usdod'                 => isset($user->preferences->usdod) ? $user->preferences->usdod : 0,
            'additional_info'       => isset($user->preferences->additional_info) ? $user->preferences->additional_info : '',
            'searching_status'      => isset($user->preferences->searching_status) ? $user->preferences->searching_status : 1,
            'date_available'        => $date_available,
            'require_sponsorship'   => isset($user->preferences->require_sponsorship) ? $user->preferences->require_sponsorship : 1,
            'current_base_salary'   => isset($user->preferences->current_base_salary) ? $user->preferences->current_base_salary : '',
            'current_contract_rate' => isset($user->preferences->current_contract_rate) ? $user->preferences->current_contract_rate : '',
            'target_base_salary'    => isset($user->preferences->target_base_salary) ? $user->preferences->target_base_salary : '',
            'target_contract_rate'  => isset($user->preferences->target_contract_rate) ? $user->preferences->target_contract_rate : '',
            'completed_steps'       => $completed_steps,
            'current_compensation'  => isset($user->preferences->current_compensation) ? $user->preferences->current_compensation : 'base_salary'
        ]);
    }

    /**
     * @param ProfileStepThreeRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function stepThreePost(ProfileStepThreeRequest $request)
    {

        $user = Auth::user();

        $user->preferences()->delete();

        //TODO why delete?
        UserPreference::create([
            'user_id'               => $user->id,
            'employment_type'       => $request->get('employment_type'),
            'work_authorization'    => $request->get('work_authorization'),
            'usdod'                 => $request->get('usdod'),
            'require_sponsorship'   => $request->get('require_sponsorship'),
            'searching_status'      => $request->get('searching_status'),
            'date_available'        => Carbon::createFromFormat('Y-m-d', $request->get('date_available'))->toDateTimeString(),
            'additional_info'       => $request->get('additional_info'),
            'current_base_salary'   => floatval($request->get('current_base_salary')),
            'current_contract_rate' => floatval($request->get('current_contract_rate')),
            'target_base_salary'    => floatval($request->get('target_base_salary')),
            'target_contract_rate'  => floatval($request->get('target_contract_rate')),
            'current_compensation'  => $request->get('current_compensation')
        ]);

        $cities = [];
        foreach ($request->get('locations') as $location) {
            $city = City::where('name', '=', $location)->first();
            if ($city instanceof City) {
                $cities[] = $city->id;
            }
        }
        $user->cities()->sync($cities);

        if($user->profile_builder_step == 2){
            $user->update(['profile_builder_step' => 3]);
        }


        return redirect(route('profile.builder.step_four_post'));
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stepFour()
    {
        $user = Auth::user();

        $completed_steps = $user->profile_builder_step;

        if($completed_steps < 3)
        {
            return redirect('/profile/builder/' . ($user->profile_builder_step + 1) );
        };

        return view('profile.builder.step_four',[
            'linkedIn'          => isset($user->networks->linkedIn) ? $user->networks->linkedIn : '' ,
            'github'            => isset($user->networks->github) ? $user->networks->github : '' ,
            'stackOverflow'     => isset($user->networks->stackOverflow) ? $user->networks->stackOverflow : '' ,
            'website'           => isset($user->networks->website) ? $user->networks->website : '' ,
            'resume'            => isset($user->networks->resume) ? $user->networks->resume : '' ,
            'twitter'           => isset($user->networks->twitter) ? $user->networks->twitter : '',
            'completed_steps'   => $completed_steps
        ]);
    }

    /**
     * @param ProfileStepFourRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function stepFourPost(ProfileStepFourRequest $request)
    {
        $user = Auth::user();

        $user->networks()->delete();
        $request->request->add(['user_id' => $user->id]);
        UserNetwork::create($request->all());

        if($user->profile_builder_step == 3){
            $user->update(['profile_builder_step' => 4]);
        }

        return redirect(route('profile.builder.store'));
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stepFive()
    {
        $user = Auth::user();

        $completed_steps = $user->profile_builder_step;

        if($completed_steps < 4)
        {
            return redirect('/profile/builder/' . ($user->profile_builder_step + 1) );
        };

        $experience = [];
        $education = [];

        foreach ($user->experience as $userExperience)
        {
            $experience['company'][] = $userExperience->company;
            $experience['title'][] = $userExperience->title;
            $experience['current'][] = $userExperience->current;
            $experience['start_date'][] = $userExperience->start_date != null ? Carbon::parse($userExperience->start_date)->format('Y-m-d') : 0-0-0;
            $experience['end_date'][] = $userExperience->end_date != null ? Carbon::parse($userExperience->end_date)->format('Y-m-d') : 0-0-0;
        }

        foreach ($user->education as $userEducation)
        {
            $education['university'][] = $userEducation->university;
            $education['degree'][] = $userEducation->degree != null ? $userEducation->degree : '';
            $education['degree_date'][] = $userEducation->degree_date != null ? $userEducation->degree_date : '';
        }


        return view('profile.builder.step_five',
            [
                'experience'        => $experience,
                'education'         => $education,
                'completed_steps'   => $completed_steps

            ]);
    }


    public function store(ProfileStoreRequest $request)
    {
        $user = Auth::user();

        if ($request->hasFile('resume')) {
            //TODO save imported file to folder
        }

            $experienceEducationData = ['experiences' => [], 'educations' => []];
        if ($request->get('type') == ProfileStoreRequest::FILL_FORM_TYPE) {
            $experienceEducationData = $request->getPreparedData();
        }

        $user->experience()->delete();

        foreach ($experienceEducationData['experiences'] as $experience) {
            $startDate = isset($experience['start_date'])
                ? Carbon::createFromFormat('Y-m-d', $experience['start_date'])->toDateTimeString()
                : null;
            $endDate = isset($experience['end_date'])
                ? Carbon::createFromFormat('Y-m-d', $experience['end_date'])->toDateTimeString()
                : null;

            UserExperience::create([
                'user_id'       => $user->id,
                'company'       => isset($experience['company']) ? $experience['company'] : '',
                'title'         => isset($experience['title']) ? $experience['title'] : '',
                'current'       => isset($experience['current']) ? $experience['current'] : 0,
                'start_date'    => $startDate,
                'end_date'      => $endDate
            ]);
        }

        $user->education()->delete();

        foreach ($experienceEducationData['educations'] as $education) {
            UserEducation::create([
                'user_id'       => $user->id,
                'university'    => isset($education['university']) ? $education['university'] : '',
                'degree'        => isset($education['degree']) ? $education['degree'] : '',
                'degree_date'   => isset($education['degree_date']) ? intval($education['degree_date']) : ''
            ]);
        }

        if($user->profile_builder_step == 4){
            $user->update(['profile_builder_step' => 5]);
        }

        return redirect(route('account.resume.view'));

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();

        if ($user->social_security == null) {
            return view('account.resume');
        }

        $profileData = [];

        $profileData['gravatar'] = (!empty($this->user->email)) ? Gravatar::fallback(url('images/user.jpg'))->get($this->user->email) : null;

        $profileData['position'] = $user->position->name;
        $profileData['additionalInfo'] = isset($user->preferences->additional_info) ? $user->preferences->additional_info : null;
        $profileData['user_city'] = isset($user->userCity->name) ? $user->userCity->name : null;
        $profileData['website'] = isset($user->networks->website) ? $user->networks->website : null;
        $profileData['email'] = $user->email;
        $profileData['skills'] = isset($user->skills) ? $user->skills : null;
        $profileData['position_roles'] = isset($user->positionRoles) ? $user->positionRoles : null;
        $profileData['educations'] = isset($user->education) ? $user->education : null;
        $profileData['experiences'] = isset($user->experience) ? $user->experience : null;
        $profileData['preferences'] = isset($user->preferences) ? $user->preferences : null;
        $profileData['profile_picture'] = isset($user->profile_picture) ? $user->profile_picture : null;

        $user_city_code_parts = explode('.', $user->userCity->subadmin1_code);
        $profileData['user_city_code'] = end($user_city_code_parts);

        return view('profile.builder.resume_view', $profileData);
    }
}
