<?php

namespace Larapen\Admin\PanelTraits;

trait Read
{
    /*
    |--------------------------------------------------------------------------
    |                                   READ
    |--------------------------------------------------------------------------
    */

    /**
     * Find and retrieve an entry in the database or fail.
     *
     * @param $id
     * @return mixed
     */
    public function getEntry($id)
    {
        $entry = $this->model->findOrFail($id);

        return $entry->withFakes();
    }

    /**
     * Get all entries from the database.
     *
     * @param null $lang
     * @return mixed
     */
    public function getEntries($lang = null)
    {
        // If lang is not set, get the default language
        if (empty($lang)) {
            $lang = \Lang::locale();
        }

        if (property_exists($this->model, 'translatable')) {
            $entries = $this->query->where('translation_lang', $lang)->get();
        } else {
            $entries = $this->query->get();
        }

        // add the fake columns for each entry
        foreach ($entries as $key => $entry) {
            $entry->addFakes($this->getFakeColumnsAsArray());
        }

        return $entries;
    }

    /**
     * Get the fields for the create or update forms.
     *
     * @param $form
     * @param bool $id
     * @return mixed
     */
    public function getFields($form, $id = false)
    {
        switch (strtolower($form)) {
            case 'create':
                return $this->getCreateFields();
                break;

            case 'update':
                return $this->getUpdateFields($id);
                break;

            default:
                return $this->getCreateFields();
                break;
        }
    }

    /**
     * Check if the create/update form has upload fields.
     * Upload fields are the ones that have "upload" => true defined on them.
     *
     * @param $form
     * @param bool $id
     * @return bool
     */
    public function hasUploadFields($form, $id = false)
    {
        $fields = $this->getFields($form, $id);
        $upload_fields = array_where($fields, function ($value, $key) {
            return isset($value['upload']) && $value['upload'] == true;
        });

        return count($upload_fields) ? true : false;
    }

    /**
     * Enable the DETAILS ROW functionality:.
     *
     * In the table view, show a plus sign next to each entry.
     * When clicking that plus sign, an AJAX call will bring whatever content you want from the EntityCrudController::showDetailsRow($id) and show it to the user.
     */
    public function enableDetailsRow()
    {
        $this->details_row = true;
    }

    /**
     * Disable the DETAILS ROW functionality:.
     */
    public function disableDetailsRow()
    {
        $this->details_row = false;
    }

    /**
     * Set the number of rows that should be show on the table page (list view).
     */
    public function setDefaultPageLength($value)
    {
        $this->default_page_length = $value;
    }

    /**
     * Get the number of rows that should be show on the table page (list view).
     */
    public function getDefaultPageLength()
    {
        // return the custom value for this crud panel, if set using setPageLength()
        if ($this->default_page_length) {
            return $this->default_page_length;
        }

        // otherwise return the default value in the config file
        if (config('larapen.admin.default_page_length')) {
            return config('larapen.admin.default_page_length');
        }

        return 25;
    }

    public function enableParentEntity()
    {
        $this->parent_entity = true;
    }
    public function disableParentEntity()
    {
        $this->parent_entity = false;
    }
    public function hasParentEntity()
    {
        return $this->parent_entity;
    }

    /*
    |--------------------------------------------------------------------------
    |                                AJAX TABLE
    |--------------------------------------------------------------------------
    */

    /**
     * Tell the list view to use AJAX for loading multiple rows.
     */
    public function enableAjaxTable()
    {
        $this->ajax_table = true;
    }

    /**
     * Check if ajax is enabled for the table view.
     * @return bool
     */
    public function ajaxTable()
    {
        return $this->ajax_table;
    }

    /**
     * Get the HTML of the cells in a table row, for a certain DB entry.
     * @param  Entity $entry A db entry of the current entity;
     * @return array         Array of HTML cell contents.
     */
    public function getRowViews($entry)
    {
        $response = [];
        foreach ($this->columns as $key => $column) {
            $response[] = $this->getCellView($column, $entry);
        }

        return $response;
    }

    /**
     * Get the HTML of a cell, using the column types.
     * @param  array $column
     * @param  Entity $entry   A db entry of the current entity;
     * @return HTML
     */
    public function getCellView($column, $entry)
    {
        if (! isset($column['type'])) {
            return \View::make('admin::panel.columns.text')->with('xPanel', $this)->with('column', $column)->with('entry', $entry)->render();
        } else {
            if (view()->exists('admin.'.$this->entity_name.'.columns.'.$column['type'])) {
                return \View::make('admin.'.$this->entity_name.'.columns.'.$column['type'])->with('xPanel', $this)->with('column', $column)->with('entry', $entry)->render();
            } else {
                if (view()->exists('admin::panel.columns.'.$column['type'])) {
                    return \View::make('admin::panel.columns.'.$column['type'])->with('xPanel', $this)->with('column', $column)->with('entry', $entry)->render();
                } else {
                    return \View::make('admin::panel.columns.text')->with('xPanel', $this)->with('column', $column)->with('entry', $entry)->render();
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    |                                EXPORT BUTTONS
    |--------------------------------------------------------------------------
    */

    /**
     * Tell the list view to show the DataTables export buttons.
     */
    public function enableExportButtons()
    {
        $this->export_buttons = true;
    }

    /**
     * Check if export buttons are enabled for the table view.
     * @return bool
     */
    public function exportButtons()
    {
        return $this->export_buttons;
    }
}
