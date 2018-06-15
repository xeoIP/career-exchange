<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Models\Category;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\CategoryRequest as StoreRequest;
use App\Http\Requests\Admin\CategoryRequest as UpdateRequest;

class SubCategoryController extends PanelController
{
    public $parentId = null;
    
    public function __construct()
    {
        parent::__construct();

        // Get the Parent ID
        $this->parentId = Request::segment(3);

        // Get Parent Category name
        $parent = Category::transById($this->parentId);
        if (empty($parent)) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Category');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/category/' . $this->parentId . '/sub_category');
        $this->xPanel->setEntityNameStrings(
            __t('subcategory') . ' &rarr; ' . '<strong>' . $parent->name . '</strong>',
            __t('subcategories') . ' &rarr; ' . '<strong>' . $parent->name . '</strong>'
        );
        $this->xPanel->enableReorder('name', 1);
        $this->xPanel->enableDetailsRow();
        $this->xPanel->orderBy('lft', 'ASC');

        $this->xPanel->enableParentEntity();
        $this->xPanel->addClause('where', 'parent_id', '=', $this->parentId);
        $this->xPanel->setParentRoute(config('larapen.admin.route_prefix', 'admin') . '/category');
        $this->xPanel->setParentEntityNameStrings('parent ' . __t('category'), 'parent ' . __t('categories'));
        $this->xPanel->allowAccess(['reorder', 'details_row', 'parent']);


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
            'label' => __t("Name"),
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
        ], 'create');
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
            'label'      => __t("Slug"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Will be automatically generated from your name, if left empty.'),
            ],
            'hint'       => __t('Will be automatically generated from your name, if left empty.'),
        ]);
        $this->xPanel->addField([
            'name'       => 'description',
            'label'      => __t("Description"),
            'type'       => 'textarea',
            'attributes' => [
                'placeholder' => __t("Description"),
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
