<?php

namespace App\Http\Controllers\Admin;

use App\Models\City;
use App\Models\Country;
use App\Models\Scopes\ActiveScope;
use App\Models\SubAdmin1;
use App\Models\SubAdmin2;
use Illuminate\Support\Facades\Request;
use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\CityRequest as StoreRequest;
use App\Http\Requests\Admin\CityRequest as UpdateRequest;

class CityController extends PanelController
{
    public $parentEntity = null;
    public $countryCode = null;
    public $admin1Code = null;
    public $admin2Code = null;

    public function __construct()
    {
        parent::__construct();

        // Parents Entities
        $parentEntities = ['country', 'loc_admin1', 'loc_admin2'];

        // Get the parent Entity slug
        $this->parentEntity = Request::segment(2);
        if (!in_array($this->parentEntity, $parentEntities)) {
            abort(404);
        }

        // Country => City
        if ($this->parentEntity == 'country') {
            // Get the Country Code
            $this->countryCode = Request::segment(3);

            // Get the Country's name
            $country = Country::find($this->countryCode);
            if (empty($country)) {
                abort(404);
            }
        }

        // Admin1 => City
        if ($this->parentEntity == 'loc_admin1') {
            // Get the Admin1 Codes
            $this->admin1Code = Request::segment(3);

            // Get the Admin1's name
            $admin1 = SubAdmin1::find($this->admin1Code);
            if (empty($admin1)) {
                abort(404);
            }

            // Get the Country Code
            $this->countryCode = $admin1->country_code;

            // Get the Country's name
            $country = Country::find($this->countryCode);
            if (empty($country)) {
                abort(404);
            }
        }

        // Admin2 => City
        if ($this->parentEntity == 'loc_admin2') {
            // Get the Admin2 Codes
            $this->admin2Code = Request::segment(3);

            // Get the Admin2's name
            $admin2 = SubAdmin2::find($this->admin2Code);
            if (empty($admin2)) {
                abort(404);
            }

            // Get the Admin1 Codes
            $this->admin1Code = $admin2->subadmin1_code;

            // Get the Admin1's name
            $admin1 = SubAdmin1::find($this->admin1Code);
            if (empty($admin1)) {
                abort(404);
            }

            // Get the Country Code
            $this->countryCode = $admin1->country_code;

            // Get the Country's name
            $country = Country::find($this->countryCode);
            if (empty($country)) {
                abort(404);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\City');
        $this->xPanel->enableAjaxTable();
        $this->xPanel->enableParentEntity();
        $this->xPanel->allowAccess(['parent']);

        // Country => City
        if ($this->parentEntity == 'country') {
            $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/country/' . $this->countryCode . '/city');
            $this->xPanel->setEntityNameStrings(
                __t('city') . ' &rarr; ' . '<strong>' . $country->name . '</strong>',
                __t('cities') . ' &rarr; ' . '<strong>' . $country->name . '</strong>'
            );
            $this->xPanel->addClause('where', 'country_code', '=', $this->countryCode);
            $this->xPanel->setParentRoute(config('larapen.admin.route_prefix', 'admin') . '/country');
            $this->xPanel->setParentEntityNameStrings(__t('country'), __t('countries'));
        }

        // Admin1 => City
        if ($this->parentEntity == 'loc_admin1') {
            $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/loc_admin1/' . $this->admin1Code . '/city');
            $this->xPanel->setEntityNameStrings(
                __t('city') . ' &rarr; ' . '<strong>' . $admin1->name . '</strong>' . ', ' . '<strong>' . $country->name . '</strong>',
                __t('cities') . ' &rarr; ' . '<strong>' . $admin1->name . '</strong>' . ', ' . '<strong>' . $country->name . '</strong>'
            );
            $this->xPanel->addClause('where', 'subadmin1_code', '=', $this->admin1Code);
            $this->xPanel->setParentRoute(config('larapen.admin.route_prefix', 'admin') . '/country/' . $this->countryCode . '/loc_admin1');
            $this->xPanel->setParentEntityNameStrings(
                __t('admin. division 1') . ' &rarr; ' . '<strong>' . $country->name . '</strong>',
                __t('admin. divisions 1') . ' &rarr; ' . '<strong>' . $country->name . '</strong>'
            );
        }

        // Admin2 => City
        if ($this->parentEntity == 'loc_admin2') {
            $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/loc_admin2/' . $this->admin2Code . '/city');
            $this->xPanel->setEntityNameStrings(
                __t('city') . ' &rarr; ' . '<strong>' . $admin2->name . '</strong>' . ', ' . '<strong>' . $admin1->name . '</strong>' . ', ' . '<strong>' . $country->name . '</strong>',
                __t('cities') . ' &rarr; ' . ' <strong>' . $admin2->name . '</strong>' . ', ' . '<strong>' . $admin1->name . '</strong>' . ', ' . '<strong>' . $country->name . '</strong>'
            );
            $this->xPanel->addClause('where', 'subadmin2_code', '=', $this->admin2Code);
            $this->xPanel->setParentRoute(config('larapen.admin.route_prefix', 'admin') . '/loc_admin1/' . $this->admin1Code . '/loc_admin2');
            $this->xPanel->setParentEntityNameStrings(
                __t('admin. division 2') . ' &rarr; ' . '<strong>' . $admin1->name . '</strong>' . ', ' . '<strong>' . $country->name . '</strong>',
                __t('admin. divisions 2') . ' &rarr; ' . '<strong>' . $admin1->name . '</strong>' . ', ' . '<strong>' . $country->name . '</strong>'
            );
        }

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
            'name'  => 'name',
            'label' => __t("Local Name"),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'asciiname',
            'label' => __t("Name"),
        ]);
        $this->xPanel->addColumn([
            'name'          => 'subadmin1_code',
            'label'         => __t("Admin1 Code"),
            'type'          => 'model_function',
            'function_name' => 'getAdmin1Html',
        ]);
        $this->xPanel->addColumn([
            'name'          => 'subadmin2_code',
            'label'         => __t("Admin2 Code"),
            'type'          => 'model_function',
            'function_name' => 'getAdmin2Html',
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => __t("Active"),
            'type'          => 'model_function',
            'function_name' => 'getActiveHtml',
        ]);

        // FIELDS
        $this->xPanel->addField([
            'name'    => 'id',
            'type'    => 'hidden',
            'default' => $this->autoIncrementId(),
        ], 'create');

        // Country => City
        if (!empty($this->countryCode)) {
            $this->xPanel->addField([
                'name'  => 'country_code',
                'type'  => 'hidden',
                'value' => $this->countryCode,
            ], 'create');
        } else {
            if (!empty($this->admin1Code)) {
                $this->xPanel->addField([
                    'name'  => 'country_code',
                    'type'  => 'hidden',
                    'value' => $this->countryCode,
                ], 'create');
            } else {
                if (!empty($this->admin2Code)) {
                    $this->xPanel->addField([
                        'name'  => 'country_code',
                        'type'  => 'hidden',
                        'value' => $this->countryCode,
                    ], 'create');
                } else {
                    $this->xPanel->addField([
                        'name'       => 'country_code',
                        'label'      => __t('Country Code'),
                        'type'       => 'select2',
                        'attribute'  => 'asciiname',
                        'model'      => 'App\Models\Country',
                        'attributes' => [
                            'placeholder' => __t('Enter the country code (ISO Code)'),
                        ],
                    ]);
                }
            }
        }

        // Admin1 => City
        if (!empty($this->admin1Code)) {
            $this->xPanel->addField([
                'name'  => 'subadmin1_code',
                'type'  => 'hidden',
                'value' => $this->admin1Code,
            ], 'create');
        } else {
            if (!empty($this->admin2Code)) {
                $this->xPanel->addField([
                    'name'  => 'subadmin1_code',
                    'type'  => 'hidden',
                    'value' => $this->admin1Code,
                ], 'create');
            } else {
                $this->xPanel->addField([
                    'name'        => 'subadmin1_code',
                    'label'       => __t("Admin1 Code"),
                    'type'        => 'select2_from_array',
                    'options'     => $this->subAdmin1s(),
                    'allows_null' => true,
                ]);
            }
        }

        // Admin2 => City
        if (!empty($this->admin2Code)) {
            $this->xPanel->addField([
                'name'  => 'subadmin2_code',
                'type'  => 'hidden',
                'value' => $this->admin2Code,
            ], 'create');
        } else {
            if (!empty($this->admin1Code)) {
                $this->xPanel->addField([
                    'name'        => 'subadmin2_code',
                    'label'       => __t("Admin2 Code"),
                    'type'        => 'select2_from_array',
                    'options'     => $this->subAdmin2s(),
                    'allows_null' => true,
                ]);
            }
        }

        $this->xPanel->addField([
            'name'       => 'name',
            'label'      => __t('Local Name'),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Local Name'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'asciiname',
            'label'      => __t("Name"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the country name (In English)'),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'latitude',
            'label'      => __t("Latitude"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t("Latitude"),
            ],
            'hint'       => __t('In decimal degrees (wgs84)'),
        ]);
        $this->xPanel->addField([
            'name'       => 'longitude',
            'label'      => __t("Longitude"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t("Longitude"),
            ],
            'hint'       => __t('In decimal degrees (wgs84)'),
        ]);
        $this->xPanel->addField([
            'name'       => 'population',
            'label'      => __t("Population"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t("Population"),
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'time_zone',
            'label'      => __t("Time Zone ID"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t('Enter the time zone ID (example: Europe/Paris)'),
            ],
            'hint'       => __t('Please check the TimeZoneId code format here:') . ' <a href="http://download.geonames.org/export/dump/timeZones.txt" target="_blank">http://download.geonames.org/export/dump/timeZones.txt</a>',
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

    /**
     * Increment new cities IDs
     *
     * @return int
     */
    public function autoIncrementId()
    {
        // Note: 10793747 is the higher ID found in Geonames cities database
        // To guard against any MySQL error we will increment new IDs from 14999999
        $startId = 14999999;

        // Count all non-Geonames entries
        $lastAddedEntry = City::withoutGlobalScope(ActiveScope::class)->where('id', '>=', $startId)->orderBy('id', 'DESC')->first();
        $lastAddedId = (!empty($lastAddedEntry)) ? $lastAddedEntry->id : $startId;

        // Set new ID
        $newId = $lastAddedId + 1;

        return $newId;
    }

    private function subAdmin1s()
    {
        // Get the Administratives Divisions
        $admins = SubAdmin1::where('country_code', $this->countryCode)->get();

        $tab = [];
        if ($admins->count() > 0) {
            foreach ($admins as $admin) {
                $tab[$admin->code] = $admin->name . ' (' . $admin->code . ')';
            }
        }

        return $tab;
    }

    private function subAdmin2s()
    {
        // Get the Admin1 Code
        if (empty($this->admin1Code)) {
            $city = $this->xPanel->model->find(Request::segment(5));
            if (!empty($city)) {
                $this->admin1Code = $city->subadmin1_code;
            }
        }

        // Get the Administratives Divisions
        $admins = SubAdmin2::where('country_code', $this->countryCode)->where('subadmin1_code', $this->admin1Code)->get();

        $tab = [];
        if ($admins->count() > 0) {
            foreach ($admins as $admin) {
                $tab[$admin->code] = $admin->name . ' (' . $admin->code . ')';
            }
        }

        return $tab;
    }
}
