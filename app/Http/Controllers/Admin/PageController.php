<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\PageRequest as StoreRequest;
use App\Http\Requests\Admin\PageRequest as UpdateRequest;

class PageController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Page');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/page');
        $this->xPanel->setEntityNameStrings(__t('page'), __t('pages'));
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
            'name'          => 'title',
            'label'         => __t("Title"),
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => __t("Active"),
            'type'          => "model_function",
            'function_name' => 'getActiveHtml',
        ]);

        // FIELDS
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
            'name'       => 'title',
            'label'      => __t("Title"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t("Title"),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'content',
            'label'      => __t("Content"),
            'type'       => (config('settings.simditor_wysiwyg')) ? 'simditor' : ((!config('settings.simditor_wysiwyg') && config('settings.ckeditor_wysiwyg')) ? 'ckeditor' : 'textarea'),
            'attributes' => [
                'placeholder' => __t("Content"),
                'id'          => 'pageContent',
                'rows'        => 20,
            ],
        ]);
        $this->xPanel->addField([
            'name'  => 'type',
            'label' => __t('Type'),
            'type'  => 'enum',
        ]);
        $this->xPanel->addField([
            'name'   => 'picture',
            'label'  => __t('Picture'),
            'type'   => 'image',
            'upload' => true,
            'disk'   => 'uploads',
        ]);
        $this->xPanel->addField([
            'name' => 'name_color',
            'label' => __t('Page Name Color'),
            'type' => 'color_picker',
            'colorpicker_options' => [
                'customClass' => 'custom-class'
            ],
        ]);
        $this->xPanel->addField([
            'name' => 'title_color',
            'label' => __t('Page Title Color'),
            'type' => 'color_picker',
            'colorpicker_options' => [
                'customClass' => 'custom-class'
            ],
        ]);
        $this->xPanel->addField([
            'name'  => 'excluded_from_footer',
            'label' => __t("Exclude from footer"),
            'type'  => 'checkbox',
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
