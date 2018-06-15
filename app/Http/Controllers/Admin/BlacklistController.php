<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use Larapen\Admin\app\Http\Requests\Request as StoreRequest;
use Larapen\Admin\app\Http\Requests\Request as UpdateRequest;

class BlacklistController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Blacklist');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/blacklist');
        $this->xPanel->setEntityNameStrings(__t('blacklist'), __t('blacklists'));

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
            'name'  => 'type',
            'label' => __t("Type"),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'entry',
            'label' => __t("Entry"),
        ]);

        // FIELDS
        $this->xPanel->addField([
            'name'  => 'type',
            'label' => __t("Type"),
            'type'  => 'enum',
        ]);
        $this->xPanel->addField([
            'name'       => 'entry',
            'label'      => __t("Entry"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t("Entry"),
            ],
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
