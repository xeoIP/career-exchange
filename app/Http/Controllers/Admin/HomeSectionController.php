<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Request as StoreRequest;
use App\Http\Requests\Admin\Request as UpdateRequest;

class HomeSectionController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\HomeSection');
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/home_section');
        $this->xPanel->setEntityNameStrings(__t('home section'), __t('home sections'));
        $this->xPanel->denyAccess(['create', 'delete']);
        $this->xPanel->allowAccess(['reorder']);
        $this->xPanel->enableReorder('name', 1);
        $this->xPanel->enableAjaxTable();
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
        ]);

        // FIELDS
        $this->xPanel->addField([
            'name'       => 'name',
            'label'      => __t("Name"),
            'type'       => 'text',
            'attributes' => [
                'placeholder' => __t("Name"),
                'disabled'    => 'disabled',
            ],
        ]);

        $section = $this->xPanel->model->find(Request::segment(3));
        if (!empty($section)) {
            // getSponsoredPosts, getLatestPosts & getFeaturedPostsCompanies
            if (in_array($section->method, ['getSponsoredPosts', 'getLatestPosts', 'getFeaturedPostsCompanies'])) {
                $maxItemsField = [
                    'name'     => 'max_items',
                    'label'    => __t("Max Items"),
                    'fake'     => true,
                    'store_in' => 'options',
                ];
                $this->xPanel->addField($maxItemsField);
            }

            // getLocations
            if ($section->method == 'getLocations') {
                $maxItemsField = [
                    'name'       => 'max_items',
                    'label'      => __t("Max Cities"),
                    'fake'       => true,
                    'store_in'   => 'options',
                    'attributes' => [
                        'placeholder' => 12,
                    ],
                ];
                $this->xPanel->addField($maxItemsField);

                $showMapField = [
                    'name'     => 'show_map',
                    'label'    => __t("Show the Country Map"),
                    'fake'     => true,
                    'store_in' => 'options',
                    'type'     => 'checkbox',
                ];
                $this->xPanel->addField($showMapField);

                $mapBackgroundColorField = [
                    'name'                => 'map_background_color',
                    'label'               => __t("Map's Background Color"),
                    'fake'                => true,
                    'store_in'            => 'options',
                    'type'                => 'color_picker',
                    'colorpicker_options' => [
                        'customClass' => 'custom-class',
                    ],
                    'attributes'          => [
                        'placeholder' => "transparent",
                    ],
                    'hint'                => __t("Enter a RGB color code or the word 'transparent'."),
                ];
                $this->xPanel->addField($mapBackgroundColorField);

                $mapBorderField = [
                    'name'                => 'map_border',
                    'label'               => __t("Map's Border"),
                    'fake'                => true,
                    'store_in'            => 'options',
                    'type'                => 'color_picker',
                    'colorpicker_options' => [
                        'customClass' => 'custom-class',
                    ],
                    'attributes'          => [
                        'placeholder' => "#c7c5c1",
                    ],
                ];
                $this->xPanel->addField($mapBorderField);

                $mapHoverBorderField = [
                    'name'                => 'map_hover_border',
                    'label'               => __t("Map's Hover Border"),
                    'fake'                => true,
                    'store_in'            => 'options',
                    'type'                => 'color_picker',
                    'colorpicker_options' => [
                        'customClass' => 'custom-class',
                    ],
                    'attributes'          => [
                        'placeholder' => "#c7c5c1",
                    ],
                ];
                $this->xPanel->addField($mapHoverBorderField);

                $mapBorderWidthField = [
                    'name'       => 'map_border_width',
                    'label'      => __t("Map's Border Width"),
                    'fake'       => true,
                    'store_in'   => 'options',
                    'attributes' => [
                        'placeholder' => 4,
                    ],
                ];
                $this->xPanel->addField($mapBorderWidthField);

                $mapColorField = [
                    'name'                => 'map_color',
                    'label'               => __t("Map's Color"),
                    'fake'                => true,
                    'store_in'            => 'options',
                    'type'                => 'color_picker',
                    'colorpicker_options' => [
                        'customClass' => 'custom-class',
                    ],
                    'attributes'          => [
                        'placeholder' => "#f2f0eb",
                    ],
                ];
                $this->xPanel->addField($mapColorField);

                $mapHoverField = [
                    'name'                => 'map_hover',
                    'label'               => __t("Map's Hover"),
                    'fake'                => true,
                    'store_in'            => 'options',
                    'type'                => 'color_picker',
                    'colorpicker_options' => [
                        'customClass' => 'custom-class',
                    ],
                    'attributes'          => [
                        'placeholder' => "#4682B4",
                    ],
                ];
                $this->xPanel->addField($mapHoverField);

                $mapWidthField = [
                    'name'       => 'map_width',
                    'label'      => __t("Map's Width"),
                    'fake'       => true,
                    'store_in'   => 'options',
                    'attributes' => [
                        'placeholder' => "300px",
                    ],
                ];
                $this->xPanel->addField($mapWidthField);

                $mapHeightField = [
                    'name'       => 'map_height',
                    'label'      => __t("Map's Height"),
                    'fake'       => true,
                    'store_in'   => 'options',
                    'attributes' => [
                        'placeholder' => "300px",
                    ],
                ];
                $this->xPanel->addField($mapHeightField);
            }

            // getSponsoredPosts
            if ($section->method == 'getSponsoredPosts') {
                $carouselAutoplayField = [
                    'name'     => 'autoplay',
                    'label'    => __t("Carousel's Autoplay"),
                    'fake'     => true,
                    'store_in' => 'options',
                    'type'     => 'checkbox',
                ];
                $this->xPanel->addField($carouselAutoplayField);

                $carouselAutoplayTimeout = [
                    'name'       => 'autoplay_timeout',
                    'label'      => __t("Carousel's Autoplay Timeout"),
                    'fake'       => true,
                    'store_in'   => 'options',
                    'attributes' => [
                        'placeholder' => 1500,
                    ],
                ];
                $this->xPanel->addField($carouselAutoplayTimeout);
            }

            // getLocations, getSponsoredPosts, getLatestPosts, getCategories & getFeaturedPostsCompanies
            if (in_array($section->method, ['getLocations', 'getSponsoredPosts', 'getLatestPosts', 'getCategories', 'getFeaturedPostsCompanies'])) {
                $cacheExpirationField = [
                    'name'     => 'cache_expiration',
                    'label'    => __t("Cache Expiration Time for this section"),
                    'fake'     => true,
                    'store_in' => 'options',
                    'attributes' => [
                        'placeholder' => __t("In minutes (e.g. 60 for 1h, 0 or empty value to disable the cache)"),
                    ],
                    'hint' => __t("In minutes (e.g. 60 for 1h, 0 or empty value to disable the cache)"),
                ];
                $this->xPanel->addField($cacheExpirationField);
            }
        }

        $activeField = [
            'name'  => 'active',
            'label' => __t("Active"),
            'type'  => 'checkbox',
        ];
        if (!empty($section) && $section->method == 'getBottomAdvertising') {
            $activeField['hint'] = __t('To enable this feature, you also need to enable advertisements at Admin panel -> Advertising');
        }
        $this->xPanel->addField($activeField);
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
