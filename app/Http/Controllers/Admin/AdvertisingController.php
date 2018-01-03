<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use Larapen\Admin\app\Http\Requests\Request as StoreRequest;
use Larapen\Admin\app\Http\Requests\Request as UpdateRequest;

class AdvertisingController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Advertising');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/advertising');
        $this->xPanel->setEntityNameStrings(__t('advertising'), __t('advertisings'));
        $this->xPanel->denyAccess(['create', 'delete']);

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
            'name'  => 'slug',
            'label' => __t("Slug"),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'provider_name',
            'label' => __t("Provider Name"),
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => __t("Active"),
            'type'          => "model_function",
            'function_name' => 'getActiveHtml',
        ]);

        // FIELDS
        $this->xPanel->addField([
            'name'       => 'provider_name',
            'label'      => __t('Provider Name'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Provider Name'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'tracking_code_large',
            'label'      => __t("Tracking Code") . " (" . __t("Large Format") . ")",
            'type'       => 'textarea',
            'attributes' => [
                'placeholder' => __t('Enter the code here. You need include &lt;script&gt; ... &lt;/script&gt; tags'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'tracking_code_medium',
            'label'      => __t("Tracking Code") . " (" . __t("Tablet Format") . ")",
            'type'       => 'textarea',
            'attributes' => [
                'placeholder' => __t('Enter the code here. You need include &lt;script&gt; ... &lt;/script&gt; tags'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'tracking_code_small',
            'label'      => __t("Tracking Code") . " (" . __t("Phone Format") . ")",
            'type'       => 'textarea',
            'attributes' => [
                'placeholder' => __t('Enter the code here. You need include &lt;script&gt; ... &lt;/script&gt; tags'),
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
