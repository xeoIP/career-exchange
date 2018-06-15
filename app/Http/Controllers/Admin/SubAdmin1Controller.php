<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\SubAdminTrait;
use App\Models\Country;
use Illuminate\Support\Facades\Request;
use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\SubAdmin1Request as StoreRequest;
use App\Http\Requests\Admin\SubAdmin1Request as UpdateRequest;

class SubAdmin1Controller extends PanelController
{
    use SubAdminTrait;
    
    public $countryCode = null;
    
    public function __construct()
    {
        parent::__construct();
    
        // Get the Country Code
        $this->countryCode = Request::segment(3);
    
        // Get the Country's name
        $country = Country::find($this->countryCode);
        if (empty($country)) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\SubAdmin1');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/country/' . $this->countryCode . '/loc_admin1');
        $this->xPanel->setEntityNameStrings(
            __t('admin. division 1') . ' &rarr; ' . '<strong>' . $country->name . '</strong>',
            __t('admin. divisions 1') . ' &rarr; ' . '<strong>' . $country->name . '</strong>'
        );
        $this->xPanel->enableAjaxTable();
    
        $this->xPanel->enableParentEntity();
        $this->xPanel->addClause('where', 'country_code', '=', $this->countryCode);
        $this->xPanel->setParentRoute(config('larapen.admin.route_prefix', 'admin') . '/country');
        $this->xPanel->setParentEntityNameStrings(__t('country'), __t('countries'));
        $this->xPanel->allowAccess(['parent']);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // COLUMNS
        $this->xPanel->addColumn([
            'name'  => 'code',
            'label' => __t("Code"),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'name',
            'label' => __t("Local Name"),
        ]);
        $this->xPanel->addColumn([
            'name'          => 'asciiname',
            'label'         => __t("Name"),
            'type'          => 'model_function',
            'function_name' => 'getNameHtml',
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => __t("Active"),
            'type'          => 'model_function',
            'function_name' => 'getActiveHtml',
        ]);
    
        // FIELDS
        $this->xPanel->addField([
            'name'  => 'country_code',
            'type'  => 'hidden',
            'value' => $this->countryCode,
        ], 'create');
        $this->xPanel->addField([
            'name'    => 'code',
            'type'    => 'hidden',
            'default' => $this->autoIncrementCode($this->countryCode . '.'),
        ], 'create');
        $this->xPanel->addField([
            'name'       => 'name',
            'label'      => __t("Local Name"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t("Local Name"),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'asciiname',
            'label'      => __t("Name"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the name (In English)'),
            ],
        ]);
        $this->xPanel->addField([
            'name'  => 'active',
            'label' => __t("Active"),
            'type'  => 'checkbox',
        ]);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
}
