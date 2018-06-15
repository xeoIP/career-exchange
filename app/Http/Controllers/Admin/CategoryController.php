<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\Request;
use Larapen\Admin\app\Http\Controllers\PanelController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\Admin\CategoryRequest as StoreRequest;
use App\Http\Requests\Admin\CategoryRequest as UpdateRequest;

class CategoryController extends PanelController
{
    public $parentId = 0;

    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Category');
        $this->xPanel->addClause('where', 'parent_id', '=', 0);
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/category');
        $this->xPanel->setEntityNameStrings(__t('category'), __t('categories'));
        $this->xPanel->enableReorder('name', 1);
        $this->xPanel->enableDetailsRow();
        $this->xPanel->allowAccess(['reorder', 'details_row']);
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
            'name'          => 'name',
            'label'         => __t("Name"),
            'type'          => 'model_function',
            'function_name' => 'getNameHtml',
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => __t("Active"),
            'type'          => 'model_function',
            'function_name' => 'getActiveHtml',
            'on_display'    => 'checkbox',
        ]);

        // FIELDS
        $this->xPanel->addField([
            'name'  => 'parent_id',
            'type'  => 'hidden',
            'value' => $this->parentId,
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
            'name'       => 'slug',
            'label'      => __t('Slug'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Will be automatically generated from your name, if left empty.'),
            ],
            'hint'       => __t('Will be automatically generated from your name, if left empty.'),
        ]);
        $this->xPanel->addField([
            'name'       => 'description',
            'label'      => __t('Description'),
            'type'       => 'textarea',
            'attributes' => [
                'placeholder' => __t('Description'),
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
