<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\PostTypeRequest as StoreRequest;
use App\Http\Requests\Admin\PostTypeRequest as UpdateRequest;

class PostTypeController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\PostType');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/p_type');
        $this->xPanel->setEntityNameStrings(__t('ad type'), __t('ad types'));
        $this->xPanel->enableReorder('name', 2);
        $this->xPanel->enableDetailsRow();
        $this->xPanel->allowAccess(['reorder', 'details_row']);
        //$this->xPanel->denyAccess(['create', 'delete']);
        $this->xPanel->orderBy('lft', 'ASC');

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // COLUMNS
        $this->xPanel->addColumn([
            'name'  => 'id',
            'label' => "ID",
        ]);
        $this->xPanel->addColumn([
            'name'  => 'name',
            'label' => __t('Name'),
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
            'label'      => __t('Name'),
            'type'       => "text",
            'attributes' => [
                'placeholder' => __t('Name'),
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
