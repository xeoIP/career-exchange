<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\TimeZoneRequest as StoreRequest;
use App\Http\Requests\Admin\TimeZoneRequest as UpdateRequest;

class TimeZoneController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\TimeZone');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/time_zone');
        $this->xPanel->setEntityNameStrings(__t('time zone'), __t('time zones'));

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // COLUMNS
        $this->xPanel->addColumn([
            'name'  => 'country_code',
            'label' => __t("Country Code"),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'time_zone_id',
            'label' => __t("Time Zone"),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'gmt',
            'label' => __t("GMT"),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'dst',
            'label' => __t("DST"),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'raw',
            'label' => __t("RAW"),
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => __t("Active"),
            'type'          => 'model_function',
            'function_name' => 'getActiveHtml',
        ]);

        // FIELDS
        $this->xPanel->addField([
            'label'     => __t("Country Code"),
            'type'      => 'select2',
            'name'      => 'country_code',
            'attribute' => 'asciiname',
            'model'     => 'App\Models\Country',
        ]);
        $this->xPanel->addField([
            'name'       => 'time_zone_id',
            'label'      => __t("Time Zone"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the TimeZone (ISO)'),
            ],
            'hint'       => __t('Please check the TimeZoneId code format here:') . ' <a href="http://download.geonames.org/export/dump/timeZones.txt" target="_blank">http://download.geonames.org/export/dump/timeZones.txt</a>',
        ]);
        $this->xPanel->addField([
            'name'       => 'gmt',
            'label'      => __t("GMT"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => 'Enter the GMT value (ISO)',
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'dst',
            'label'      => __t("DST"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => 'Enter the DST value (ISO)',
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'raw',
            'label'      => __t("GMT"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the RAW value (ISO)'),
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
