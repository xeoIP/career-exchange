<?php

namespace Larapen\Admin\app\Http\Controllers;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\Admin\LanguageRequest as StoreRequest;
use App\Http\Requests\Admin\LanguageRequest as UpdateRequest;

class LanguageController extends PanelController
{
    /**
     * LanguageController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Language');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/language');
        $this->xPanel->setEntityNameStrings(__t('language'), __t('languages'));

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // COLUMNS
        $this->xPanel->addColumn([
            'name'  => 'name',
            'label' => trans('admin::messages.language_name'),
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => trans('admin::messages.active'),
            'type'          => "model_function",
            'function_name' => 'getActiveHtml',
        ]);
        $this->xPanel->addColumn([
            'name'          => 'default',
            'label'         => trans('admin::messages.default'),
            'type'          => "model_function",
            'function_name' => 'getDefaultHtml',
        ]);

        // FIELDS
        $this->xPanel->addField([
            'name'       => 'name',
            'label'      => trans('admin::messages.language_name'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => trans('admin::messages.language_name'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'native',
            'label'      => trans('admin::messages.native_name'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => trans('admin::messages.native_name'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'abbr',
            'label'      => trans('admin::messages.code_iso639-1'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => trans('admin::messages.code_iso639-1'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'locale',
            'label'      => __t('Locale Code (E.g. en_US)'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Locale Code (E.g. en_US)'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'script',
            'label'      => __t('Script'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the script code (latn, etc.)'),
            ],
        ]);
        $this->xPanel->addField([
            'name'  => 'russian_pluralization',
            'label' => __t('Russian Pluralization'),
            'type'  => 'checkbox',
        ]);
        $this->xPanel->addField([
            'name'  => 'active',
            'label' => trans('admin::messages.active'),
            'type'  => 'checkbox',
        ]);
        $this->xPanel->addField([
            'name'  => 'default',
            'label' => trans('admin::messages.default'),
            'type'  => 'checkbox',
        ], 'update');
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
