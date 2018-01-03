<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Models\Gender;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Larapen\Admin\app\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\UserRequest as StoreRequest;
use App\Http\Requests\Admin\UserRequest as UpdateRequest;

class UserController extends PanelController
{
    use VerificationTrait;
    
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\User');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/user');
        $this->xPanel->setEntityNameStrings(__t('user'), __t('users'));
        $this->xPanel->enableAjaxTable();
        $this->xPanel->orderBy('created_at', 'DESC');

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        if (Request::segment(2) != 'account') {
            // COLUMNS
            $this->xPanel->addColumn([
                'name'  => 'id',
                'label' => "ID",
            ]);
            $this->xPanel->addColumn([
                'name'  => 'created_at',
                'label' => __t("Date"),
                'type'  => 'datetime',
            ]);
            $this->xPanel->addColumn([
                'name'  => 'name',
                'label' => __t("Name"),
            ]);
            $this->xPanel->addColumn([
                'name'  => 'email',
                'label' => __t("Email"),
            ]);
            $this->xPanel->addColumn([
                'name'      => 'user_type_id',
                'label'     => __t("Type"),
                'model'     => 'App\Models\UserType',
                'entity'    => 'userType',
                'attribute' => 'name',
                'type'      => 'select',
            ]);
            $this->xPanel->addColumn([
                'label'         => __t("Country"),
                'name'          => 'country_code',
                'type'          => 'model_function',
                'function_name' => 'getCountryHtml',
            ]);
            $this->xPanel->addColumn([
                'name'          => 'verified_email',
                'label'         => __t("Verified Email"),
                'type'          => 'model_function',
                'function_name' => 'getVerifiedEmailHtml',
            ]);
            $this->xPanel->addColumn([
                'name'          => 'verified_phone',
                'label'         => __t("Verified Phone"),
                'type'          => 'model_function',
                'function_name' => 'getVerifiedPhoneHtml',
            ]);

            // FIELDS
            $this->xPanel->addField([
                'name'       => 'email',
                'label'      => __t("Email"),
                'type'       => 'email',
                'attributes' => [
                    'placeholder' => __t("Email"),
                ],
            ]);
            $this->xPanel->addField([
                'name'       => 'password',
                'label'      => __t("Password"),
                'type'       => 'password',
                'attributes' => [
                    'placeholder' => __t("Password"),
                ],
            ], 'create');
            $this->xPanel->addField([
                'label'       => __t("Gender"),
                'name'        => 'gender_id',
                'type'        => 'select2_from_array',
                'options'     => $this->gender(),
                'allows_null' => false,
            ]);
            $this->xPanel->addField([
                'name'       => 'name',
                'label'      => __t("Name"),
                'type'       => 'text',
                'attributes' => [
                    'placeholder' => __t("Name"),
                ],
            ]);
            $this->xPanel->addField([
                'name'       => 'phone',
                'label'      => __t("Phone"),
                'type'       => 'text',
                'attributes' => [
                    'placeholder' => __t("Phone"),
                ],
            ]);
            $this->xPanel->addField([
                'name'  => 'phone_hidden',
                'label' => __t("Phone hidden"),
                'type'  => 'checkbox',
            ]);
            $this->xPanel->addField([
                'label'     => __t("Country"),
                'name'      => 'country_code',
                'model'     => 'App\Models\Country',
                'entity'    => 'country',
                'attribute' => 'asciiname',
                'type'      => 'select2',
            ]);
            $this->xPanel->addField([
                'name'      => 'user_type_id',
                'label'     => __t("Type"),
                'model'     => 'App\Models\UserType',
                'entity'    => 'userType',
                'attribute' => 'name',
                'type'      => 'select2',
            ]);
            $this->xPanel->addField([
                'name'  => 'is_admin',
                'label' => __t("Is admin"),
                'type'  => 'checkbox',
            ]);
            $this->xPanel->addField([
                'name'  => 'verified_email',
                'label' => __t("Verified Email"),
                'type'  => 'checkbox',
            ]);
            $this->xPanel->addField([
                'name'  => 'verified_phone',
                'label' => __t("Verified Phone"),
                'type'  => 'checkbox',
            ]);
            $this->xPanel->addField([
                'name'  => 'blocked',
                'label' => __t("Blocked"),
                'type'  => 'checkbox',
            ]);
			$this->xPanel->addField([
				'name'       => 'ip_addr',
				'label'      => "IP",
				'type'       => 'text',
				'attributes' => [
					'disabled' => true,
				],
			]);
        }

        // Encrypt password
        if (Input::filled('password')) {
            Input::merge(['password' => bcrypt(Input::get('password'))]);
        }
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }

    public function account()
    {
        // FIELDS
        $this->xPanel->addField([
            'label'       => __t("Gender"),
            'name'        => 'gender_id',
            'type'        => 'select2_from_array',
            'options'     => $this->gender(),
            'allows_null' => false,
        ]);
        $this->xPanel->addField([
            'name'        => 'name',
            'label'       => __t("Name"),
            'type'        => 'text',
            'placeholder' => __t("Name"),
        ]);
        $this->xPanel->addField([
            'name'        => 'email',
            'label'       => __t("Email"),
            'type'        => 'email',
            'placeholder' => __t("Email"),
        ]);
        $this->xPanel->addField([
            'name'        => 'password',
            'label'       => __t("Password"),
            'type'        => 'password',
            'placeholder' => __t("Password"),
        ]);
        $this->xPanel->addField([
            'name'        => 'phone',
            'label'       => __t("Phone"),
            'type'        => 'text',
            'placeholder' => __t("Phone"),
        ]);
        $this->xPanel->addField([
            'name'  => 'phone_hidden',
            'label' => "Phone hidden",
            'type'  => 'checkbox',
        ]);
        $this->xPanel->addField([
            'label'     => __t("Country"),
            'name'      => 'country_code',
            'model'     => 'App\Models\Country',
            'entity'    => 'country',
            'attribute' => 'asciiname',
            'type'      => 'select2',
        ]);
        $this->xPanel->addField([
            'name'      => 'user_type_id',
            'label'     => __t("Type"),
            'model'     => 'App\Models\UserType',
            'entity'    => 'userType',
            'attribute' => 'name',
            'type'      => 'select2',
        ]);

        // Get logged user
        if (Auth::check()) {
            return $this->edit(Auth::user()->id);
        } else {
            abort(403, 'Not allowed.');
        }
    }

    public function gender()
    {
        $entries = Gender::trans()->get();
    
        return $this->getTranslatedArray($entries);
    }
}
