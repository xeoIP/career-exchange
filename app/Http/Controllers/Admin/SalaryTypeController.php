<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use Larapen\Admin\app\Http\Requests\Request as StoreRequest;
use Larapen\Admin\app\Http\Requests\Request as UpdateRequest;

class SalaryTypeController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\SalaryType');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/salary_type');
        $this->xPanel->setEntityNameStrings(__t('salary type'), __t('salary types'));
        $this->xPanel->enableDetailsRow();
        $this->xPanel->allowAccess(['details_row']);
        //$this->xPanel->denyAccess(['create', 'delete']);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // COLUMNS
        $this->xPanel->addColumn([
            'name'  => "id",
            'label' => "ID",
        ]);
        $this->xPanel->addColumn([
            'name'  => "name",
            'label' => __t("Name"),
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => __t("Active"),
            'type'          => 'model_function',
            'function_name' => 'getActiveHtml',
        ]);

        // FIELDS
        $this->xPanel->addField([
            'name'       => "name",
            'label'      => __t("Name"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t("Name"),
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
