<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\PackageRequest as StoreRequest;
use App\Http\Requests\Admin\PackageRequest as UpdateRequest;

class PackageController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Package');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/package');
        $this->xPanel->setEntityNameStrings(__t('package'), __t('packages'));
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
            'name'  => 'name',
            'label' => __t("Name"),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'price',
            'label' => __t("Price"),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'currency_code',
            'label' => __t("Currency"),
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => __t("Active"),
            'type'          => 'model_function',
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
            'name'       => 'short_name',
            'label'      => __t('Short Name'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Short Name'),
            ],
            'hint'  => __t('Short name for ribbon label.'),
        ]);
        $this->xPanel->addField([
            'name'  => 'ribbon',
            'label' => __t('Ribbon'),
            'type'  => 'enum',
            'hint'  => __t('Show ads with ribbon when viewing ads in search results list.'),
        ]);
        $this->xPanel->addField([
            'name'  => 'has_badge',
            'label' => __t("Show ads with a badge (in addition)"),
            'type'  => 'checkbox',
        ]);
        $this->xPanel->addField([
            'name'        => 'price',
            'label'       => __t("Price"),
            'type'        => 'text',
            'placeholder' => __t("Price"),
        ]);
        $this->xPanel->addField([
            'label'     => __t("Currency"),
            'name'      => 'currency_code',
            'model'     => 'App\Models\Currency',
            'entity'    => 'currency',
            'attribute' => 'name',
            'type'      => 'select2',
        ]);
        $this->xPanel->addField([
            'name'       => 'duration',
            'label'      => __t('Duration'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Duration (in days)'),
            ],
            'hint'  => __t('Duration to show posts (in days). You need to schedule the AdsCleaner command.'),
        ]);
        $this->xPanel->addField([
            'name'       => 'description',
            'label'      => __t('Description'),
            'type'       => 'text',
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
