<?php

namespace Larapen\Admin\app\Http\Controllers\Features;

trait AjaxTable
{
    /**
     * Respond with the JSON of one or more rows, depending on the POST parameters.
     * @return JSON Array of cells in HTML form.
     */
    public function search()
    {
        $this->xPanel->hasAccessOrFail('list');

        // crate an array with the names of the searchable columns
        $columns = collect($this->xPanel->columns)
            ->reject(function ($column, $key) {
                // the select_multiple columns are not searchable
                return isset($column['type']) && $column['type'] == 'select_multiple';
            })
            ->pluck('name')
            // add the primary key, otherwise the buttons won't work
            ->merge($this->xPanel->model->getKeyName())
            ->toArray();

        // structure the response in a DataTable-friendly way
        $dataTable = new \LiveControl\EloquentDataTable\DataTable($this->xPanel->query, $columns);

        // make the datatable use the column types instead of just echoing the text
        $dataTable->setFormatRowFunction(function ($entry) {
            // get the actual HTML for each row's cell
            $row_items = $this->xPanel->getRowViews($entry, $this->xPanel);

            // add the buttons as the last column
            if ($this->xPanel->buttons->where('stack', 'line')->count()) {
                $row_items[] = \View::make('admin::panel.inc.button_stack', ['stack' => 'line'])
                    ->with('xPanel', $this->xPanel)
                    ->with('entry', $entry)
                    ->render();
            }

            // add the details_row buttons as the first column
            if ($this->xPanel->details_row) {
                array_unshift($row_items, \View::make('admin::panel.columns.details_row_button')
                    ->with('xPanel', $this->xPanel)
                    ->with('entry', $entry)
                    ->render());
            }

            return $row_items;
        });

        return $dataTable->make();
    }
}
