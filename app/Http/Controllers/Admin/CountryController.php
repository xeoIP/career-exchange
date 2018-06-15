<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\CountryRequest as StoreRequest;
use App\Http\Requests\Admin\CountryRequest as UpdateRequest;

class CountryController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Country');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/country');
        $this->xPanel->setEntityNameStrings(__t('country'), __t('countries'));
        $this->xPanel->enableAjaxTable();

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
            'name'       => 'code',
            'label'      => __t('Code'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the country code (ISO Code)'),
            ],
        ], 'create');
        $this->xPanel->addField([
            'name'       => 'name',
            'label'      => __t('Local Name'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Local Name'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'asciiname',
            'label'      => __t("Name"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the country name (In English)'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'capital',
            'label'      => __t('Capital') . ' (' . __t('Optional') . ')',
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Capital'),
            ],
        ]);
        $this->xPanel->addField([
            'name'      => 'continent_code',
            'label'     => __t('Continent'),
            'type'      => 'select2',
            'attribute' => 'name',
            'model'     => 'App\Models\Continent',
        ]);
        $this->xPanel->addField([
            'name'       => 'tld',
            'label'      => __t('TLD') . ' (' . __t('Optional') . ')',
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the country tld (E.g. .bj for Benin)'),
            ],
        ]);
        $this->xPanel->addField([
            'name'      => 'currency_code',
            'label'     => __t("Currency Code"),
            'type'      => 'select2',
            'attribute' => 'code',
            'model'     => 'App\Models\Currency',
        ]);
        $this->xPanel->addField([
            'name'       => 'phone',
            'label'      => __t("Phone Ind.") . ' (' . __t('Optional') . ')',
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the country phone ind. (E.g. +229 for Benin)'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'languages',
            'label'      => __t("Languages"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the locale code (ISO) separate with comma'),
            ],
        ]);
        $this->xPanel->addField([
            'name'  => 'admin_type',
            'label' => __t("Administrative Division's Type"),
            'type'  => 'enum',
            'hint'  => __t("eg. 0 => Default value, 1 => Admin. Division 1, 2 => Admin. Division 2"),
        ]);
        $this->xPanel->addField([
            'name'  => 'admin_field_active',
            'label' => __t("Active Administrative Division's Field"),
            'type'  => 'checkbox',
            'hint'  => __t("Active the administrative division's field in the items form. You need to set the :admin_type_hint to 1 or 2.", [
                'admin_type_hint' => __t("Administrative Division's Type"),
            ]),
        ]);
        /*
        $this->xPanel->addField([
            'name'  => 'active',
            'label' => __t("Active"),
            'type'  => 'checkbox',
        ]);
        */
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
