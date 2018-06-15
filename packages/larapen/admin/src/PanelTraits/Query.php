<?php

namespace Larapen\Admin\PanelTraits;

trait Query
{
    // ----------------
    // ADVANCED QUERIES
    // ----------------

    /**
     * Add another clause to the query (for ex, a WHERE clause).
     *
     * Examples:
     * // $this->xPanel->addClause('active');
     * $this->xPanel->addClause('type', 'car');
     * $this->xPanel->addClause('where', 'name', '==', 'car');
     * $this->xPanel->addClause('whereName', 'car');
     * $this->xPanel->addClause('whereHas', 'posts', function($query) {
     *     $query->activePosts();
     *     });
     *
     *
     * @param $function
     * @return mixed
     */
    public function addClause($function)
    {
        return call_user_func_array([$this->query, $function], array_slice(func_get_args(), 1, 3));
    }

    /**
     * Order the results of the query in a certain way.
     *
     * @param $field
     * @param string $order
     * @return mixed
     */
    public function orderBy($field, $order = 'asc')
    {
        return $this->query->orderBy($field, $order);
    }

    /**
     * Group the results of the query in a certain way.
     *
     * @param $field
     * @return mixed
     */
    public function groupBy($field)
    {
        return $this->query->groupBy($field);
    }

    /**
     * Limit the number of results in the query.
     *
     * @param $number
     * @return mixed
     */
    public function limit($number)
    {
        return $this->query->limit($number);
    }
}
