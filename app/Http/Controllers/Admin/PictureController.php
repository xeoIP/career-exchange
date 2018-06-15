<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use Larapen\Admin\app\Http\Controllers\PanelController;
use Larapen\Admin\app\Http\Requests\Request as StoreRequest;
use Larapen\Admin\app\Http\Requests\Request as UpdateRequest;

class PictureController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Picture');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/picture');
        $this->xPanel->setEntityNameStrings(__t('picture'), __t('pictures'));
        $this->xPanel->enableAjaxTable();
        $this->xPanel->orderBy('created_at', 'DESC');
        $this->xPanel->removeButton('create');

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
            'name'          => 'filename',
            'label'         => __t("Filename"),
            'type'          => 'model_function',
            'function_name' => 'getFilenameHtml',
        ]);
        $this->xPanel->addColumn([
            'name'          => 'post_id',
            'label'         => __t("Ad"),
            'type'          => 'model_function',
            'function_name' => 'getPostTitleHtml',
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => __t("Active"),
            'type'          => 'model_function',
            'function_name' => 'getActiveHtml',
        ]);

        // FIELDS
        $this->xPanel->addField([
            'name'  => 'post_id',
            'type'  => 'hidden',
            'value' => Input::get('post_id'),
        ], 'create');
        $this->xPanel->addField([
            'name'   => 'filename',
            'label'  => __t("Picture"),
            'type'   => 'image',
            'upload' => true,
            'disk'   => 'uploads',
        ]);
        $this->xPanel->addField([
            'name'  => 'active',
            'label' => __t("Active"),
            'type'  => 'checkbox',
            'value' => 1,
        ]);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud($request);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud($request);
    }
}
