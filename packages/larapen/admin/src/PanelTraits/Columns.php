<?php

namespace Larapen\Admin\PanelTraits;

trait Columns
{
    // ------------
    // COLUMNS
    // ------------

    /**
     * Add a bunch of column names and their details to the CRUD object.
     *
     * @param $columns
     */
    public function setColumns($columns)
    {
        // clear any columns already set
        $this->columns = [];

        // if array, add a column for each of the items
        if (is_array($columns) && count($columns)) {
            foreach ($columns as $key => $column) {
                // if label and other details have been defined in the array
                if (is_array($columns[0])) {
                    $this->addColumn($column);
                } else {
                    $this->addColumn([
                                    'name'  => $column,
                                    'label' => ucfirst($column),
                                    'type'  => 'text',
                                ]);
                }
            }
        }

        if (is_string($columns)) {
            $this->addColumn([
                                'name'  => $columns,
                                'label' => ucfirst($columns),
                                'type'  => 'text',
                                ]);
        }

        // This was the old setColumns() function, and it did not work:
        // $this->columns = array_filter(array_map([$this, 'addDefaultTypeToColumn'], $columns));
    }

    /**
     * Add a column at the end of to the CRUD object's "columns" array.
     *
     * @param $column
     * @return array|ø
     */
    public function addColumn($column)
    {
        // if a string was passed, not an array, change it to an array
        if (! is_array($column)) {
            $column = ['name' => $column];
        }

        // make sure the column has a type
        $column_with_details = $this->addDefaultTypeToColumn($column);

        // make sure the column has a label
        $column_with_details = $this->addDefaultLabel($column);

        return array_filter($this->columns[] = $column_with_details);
    }

    /**
     * Add multiple columns at the end of the CRUD object's "columns" array.
     *
     * @param $columns
     */
    public function addColumns($columns)
    {
        if (count($columns)) {
            foreach ($columns as $key => $column) {
                $this->addColumn($column);
            }
        }
    }

    /**
     * Add the default column type to the given Column, inferring the type from the database column type.
     *
     * @param $column
     * @return array|bool
     */
    public function addDefaultTypeToColumn($column)
    {
        if (array_key_exists('name', (array) $column)) {
            $default_type = $this->getFieldTypeFromDbColumnType($column['name']);

            return array_merge(['type' => $default_type], $column);
        }

        return false;
    }

    /**
     * If a field or column array is missing the "label" attribute, an ugly error would be show.
     * So we add the field Name as a label - it's better than nothing.
     *
     * @param $array
     * @return array
     */
    public function addDefaultLabel($array)
    {
        if (! array_key_exists('label', (array) $array) && array_key_exists('name', (array) $array)) {
            $array = array_merge(['label' => ucfirst($this->makeLabel($array['name']))], $array);

            return $array;
        }

        return $array;
    }

    /**
     * Remove multiple columns from the CRUD object using their names.
     *
     * @param $columns
     */
    public function removeColumns($columns)
    {
        $this->columns = $this->remove('columns', $columns);
    }

    /**
     * Remove a column from the CRUD object using its name.
     *
     * @param  [column array]
     */
    public function removeColumn($column)
    {
        return $this->removeColumns([$column]);
    }


    /**
     * @param $entity
     * @param $fields
     * @return array
     */
    public function remove($entity, $fields)
    {
        return array_values(array_filter($this->{$entity}, function ($field) use ($fields) {
            return ! in_array($field['name'], (array) $fields);
        }));
    }

    /**
     * Change attributes for multiple columns.
     *
     * @param $columns
     * @param $attributes
     */
    public function setColumnsDetails($columns, $attributes)
    {
        $this->sync('columns', $columns, $attributes);
    }

    /**
     * Change attributes for a certain column.
     *
     * @param $column
     * @param $attributes
     */
    public function setColumnDetails($column, $attributes)
    {
        $this->setColumnsDetails([$column], $attributes);
    }

    /**
     * ALIAS of setColumnOrder($columns)
     *
     * @param $columns
     */
    public function setColumnsOrder($columns)
    {
        $this->setColumnOrder($columns);
    }

    // ------------
    // TONE FUNCTIONS - UNDOCUMENTED, UNTESTED, SOME MAY BE USED
    // ------------
    /**
     * @return mixed
     */
    public function getColumns()
    {
        return $this->sort('columns');
    }

    /**
     * @param $order
     */
    public function orderColumns($order)
    {
        $this->setSort('columns', (array) $order);
    }
}
