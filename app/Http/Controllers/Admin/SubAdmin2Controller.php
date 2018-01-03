<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\SubAdminTrait;
use App\Models\Country;
use App\Models\SubAdmin1;
use Illuminate\Support\Facades\Request;
use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\SubAdmin2Request as StoreRequest;
use App\Http\Requests\Admin\SubAdmin2Request as UpdateRequest;

class SubAdmin2Controller extends PanelController
{
    use SubAdminTrait;
    
    public $parentEntity = null;
    public $countryCode = null;
    public $admin1Code = null;
    
    public function __construct()
    {
        parent::__construct();
    
        // Parents Entities
        $parentEntities = ['loc_admin1'];
    
        // Get the parent Entity slug
        $this->parentEntity = Request::segment(2);
        if (!in_array($this->parentEntity, $parentEntities)) {
            abort(404);
        }
    
        // Admin1 => Admin2
        if ($this->parentEntity == 'loc_admin1') {
            // Get the Admin1 Codes
            $this->admin1Code = Request::segment(3);
        
            // Get the Admin1's name
            $admin1 = SubAdmin1::find($this->admin1Code);
            if (empty($admin1)) {
                abort(404);
            }
        
            // Get the Country Code
            $this->countryCode = $admin1->country_code;
        
            // Get the Country's name
            $country = Country::find($this->countryCode);
            if (empty($country)) {
                abort(404);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\SubAdmin2');
        $this->xPanel->enableAjaxTable();
        $this->xPanel->enableParentEntity();
        $this->xPanel->allowAccess(['parent']);
    
        // Admin1 => Admin2
        if ($this->parentEntity == 'loc_admin1') {
            $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/loc_admin1/' . $this->admin1Code . '/loc_admin2');
            $this->xPanel->setEntityNameStrings(
                __t('admin. division 2') . ' &rarr; ' . '<strong>' . $admin1->name . '</strong>' . ', ' . '<strong>' . $country->name . '</strong>',
                __t('admin. divisions 2') . ' &rarr; ' . '<strong>' . $admin1->name . '</strong>' . ', ' . '<strong>' . $country->name . '</strong>'
            );
            $this->xPanel->addClause('where', 'subadmin1_code', '=', $this->admin1Code);
            $this->xPanel->setParentRoute(config('larapen.admin.route_prefix', 'admin') . '/country/' . $this->countryCode . '/loc_admin1');
            $this->xPanel->setParentEntityNameStrings(
                __t('admin. division 1') . ' &rarr; ' . '<strong>' . $country->name . '</strong>',
                __t('admin. divisions 1') . ' &rarr; ' . '<strong>' . $country->name . '</strong>'
            );
        }

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
            'name'  => 'subadmin1_code',
            'type'  => 'hidden',
            'value' => $this->admin1Code,
        ], 'create');
        $this->xPanel->addField([
            'name'    => 'code',
            'type'    => 'hidden',
            'default' => $this->autoIncrementCode($this->admin1Code . '.'),
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
