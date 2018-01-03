<?php

/*
------------------------------------------------------------------------------------
The "field" field value for "settings" table
------------------------------------------------------------------------------------
text            => {"name":"value","label":"Value","type":"text"}
textarea        => {"name":"value","label":"Value","type":"textarea"}
checkbox        => {"name":"value","label":"Activation","type":"checkbox"}
upload (image)  => {"name":"value","label":"Value","type":"image","upload":"true","disk":"uploads","default":"images/logo@2x.png"}
selectbox       => {"name":"value","label":"Value","type":"select_from_array","options":OPTIONS}
                => {"default":"Default","blue":"Blue","yellow":"Yellow","green":"Green","red":"Red"}
                => {"smtp":"SMTP","mailgun":"Mailgun","mandrill":"Mandrill","ses":"Amazon SES","mail":"PHP Mail","sendmail":"Sendmail"}
                => {"sandbox":"sandbox","live":"live"}
------------------------------------------------------------------------------------
*/

namespace Larapen\Admin\app\Http\Controllers;

use App\Http\Requests\Admin\SettingRequest as StoreRequest;
use App\Http\Requests\Admin\SettingRequest as UpdateRequest;

class SettingController extends PanelController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\Setting');
        $this->xPanel->setEntityNameStrings(__t('setting'), __t('settings'));
        $this->xPanel->setRoute(config('larapen.admin.route_prefix', 'admin') . '/setting');
        $this->xPanel->enableReorder('name', 1);
        $this->xPanel->allowAccess(['reorder']);
        $this->xPanel->denyAccess(['create', 'delete']);
        $this->xPanel->setDefaultPageLength(100);

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // COLUMNS
        $this->xPanel->addColumn([
            'name'  => 'lft',
            'label' => "#",
        ]);
        $this->xPanel->addColumn([
            'name'  => 'name',
            'label' => "Name",
        ]);
        $this->xPanel->addColumn([
            'name'          => 'value',
            'label'         => "Value",
            'type'          => "model_function",
            'function_name' => 'getValueHtml',
        ]);
        $this->xPanel->addColumn([
            'name'  => 'description',
            'label' => "Description",
        ]);

        // FIELDS
        $this->xPanel->addField([
            'name'       => 'name',
            'label'      => 'Name',
            'type'       => 'text',
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ]);
        $this->xPanel->addField([
            'name'       => 'description',
            'label'      => 'Description',
            'type'       => 'textarea',
            'attributes' => [
                'disabled' => 'disabled',
            ],
        ]);
        $this->xPanel->addField([
            'name'  => 'value',
            'label' => 'Value',
            'type'  => 'text',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // if view_table_permission is false, abort
        $this->xPanel->hasAccessOrFail('list');
        $this->xPanel->addClause('where', 'active', 1);

        $this->data['entries'] = $this->xPanel->getEntries();
        $this->data['xPanel'] = $this->xPanel;
        $this->data['title'] = ucfirst($this->xPanel->entity_name_plural);

        return view('admin::panel.list', $this->data);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    /**
     * @param $id
     * @param null $childId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id, $childId = null)
    {
        $this->xPanel->hasAccessOrFail('update');

        if (!empty($childId)) {
            $id = $childId;
        }

        $this->data['entry'] = $this->xPanel->getEntry($id);

        // Translate possible settings field value
        $fieldValue = (array)json_decode($this->data['entry']->field);
        if (isset($fieldValue['hint'])) {
            $fieldValue['hint'] = trans('admin::messages.' . $fieldValue['hint']);
        }

        $this->xPanel->addField($fieldValue);
        $this->data['xPanel'] = $this->xPanel;
        $this->data['fields'] = $this->xPanel->getUpdateFields($id);
        $this->data['title'] = trans('admin::messages.edit') . ' ' . $this->xPanel->entity_name;

        $this->data['id'] = $id;

        return view('admin::panel.edit', $this->data);
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
}
