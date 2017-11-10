<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request as Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class Search
{
    public $country;
    public $lang;
    public static $queryLength = 1; // Minimum query characters
    public static $distance = 100; // km
    public $maxDistance = 500; // km
    public $perPage = 12;
    public $currentPage = 0;
    protected $table = 'posts';
    protected $searchable = [
        'columns' => [
            'a.title'       => 10,
            'a.description' => 10,
            'cl.name'       => 5,
            //'cl.description'  => 1,
            //'cpl.description' => 1,
        ],
        'joins'   => [
            'categories as c' => ['c.id', 'posts.category_id'],
            'categories as cp' => ['cp.id', 'c.parent_id'],
        ],
    ];
    public $forceAverage = true; // Force relevance's average
    public $average = 1; // Set relevance's average

    // Pre-Search vars
    public $city = null;
    public $admin = null;

    /**
     * Ban this words in query search
     * @var array
     */
    //protected $banWords = ['sell', 'buy', 'vendre', 'vente', 'achat', 'acheter', 'ses', 'sur', 'de', 'la', 'le', 'les', 'des', 'pour', 'latest'];
    protected $banWords = [];
    protected $arrSql = [
        'select'  => [],
        'join'    => [],
        'where'   => [],
        'groupBy' => [],
        'having'  => [],
        'orderBy' => [],
    ];
    protected $bindings = [];
    protected $sql = [
        'select'  => '',
        'from'    => '',
        'join'    => '',
        'where'   => '',
        'groupBy' => '',
        'having'  => '',
        'orderBy' => '',
    ];
    // Only for WHERE
    protected $filters = [
        'type'       => 'a.post_type_id',
        'minSalary'  => 'a.salary_min',
        'maxSalary'  => 'a.salary_max',
        'postedDate' => 'a.created_at',
    ];
    protected $orderMapping = [
        'salaryAsc'  => ['name' => 'a.salary_max', 'order' => 'ASC'],
        'salaryDesc' => ['name' => 'a.salary_max', 'order' => 'DESC'],
        'relevance'  => ['name' => 'relevance', 'order' => 'DESC'],
        'date'       => ['name' => 'a.created_at', 'order' => 'DESC'],
    ];

    /**
     * Search constructor.
     * @param array $preSearch
     */
    public function __construct($preSearch = [])
    {
        // Pre-Search
        if (isset($preSearch['city']) && !empty($preSearch['city'])) {
            $this->city = $preSearch['city'];
        }
        if (isset($preSearch['admin']) && !empty($preSearch['admin'])) {
            $this->admin = $preSearch['admin'];
        }

        // Ads per page
        $this->perPage = (is_numeric(config('settings.posts_per_page'))) ? config('settings.posts_per_page') : $this->perPage;
        if ($this->perPage < 4) $this->perPage = 4;
        if ($this->perPage > 40) $this->perPage = 40;

        // Init.
        array_push($this->banWords, strtolower(config('country.name')));
        $this->arrSql = Arr::toObject($this->arrSql);
        $this->sql = Arr::toObject($this->sql);
        $this->sql->select = '';
        $this->sql->from = '';
        $this->sql->join = '';
        $this->sql->where = '';
        $this->sql->groupBy = '';
        $this->sql->having = '';
        $this->sql->orderBy = '';

        // Build the global SQL
        $this->arrSql->select[] = "a.*";
        // Post category relation
        $this->arrSql->join[] = "INNER JOIN " . table('categories') . " as c ON c.id=a.category_id AND c.active=1";
        // Category parent relation
        $this->arrSql->join[] = "LEFT JOIN " . table('categories') . " as cp ON cp.id=c.parent_id AND cp.active=1";
        // Post payment relation
        // $this->arrSql->join[] = "LEFT JOIN " . table('payments') . " as py ON py.post_id=a.id";
        $this->arrSql->join[] = "LEFT JOIN (SELECT MAX(id) max_id, post_id FROM " . table('payments') . " GROUP BY post_id) mpy ON mpy.post_id = a.id AND a.featured=1";
        $this->arrSql->join[] = "LEFT JOIN " . table('payments') . " as py ON py.id=mpy.max_id";
        $this->arrSql->join[] = "LEFT JOIN " . table('packages') . " as p ON p.id=py.package_id";
        $this->arrSql->where = [
            'a.country_code' 	=> " = :countryCode",
            '(a.verified_email' => " = 1 AND a.verified_phone = 1)",
            'a.archived'     	=> " != 1",
            'a.deleted_at'   	=> " IS NULL",
        ];
        $this->bindings['countryCode'] = config('country.code');

        // Check reviewed ads
        if (config('settings.posts_review_activation')) {
            $this->arrSql->where['a.reviewed'] = " = 1";
        }

        // Priority setter
        if (Input::filled('distance') and is_numeric(Input::get('distance')) and Input::get('distance') > 0) {
            self::$distance = Input::get('distance');
            if (Input::get('distance') > $this->maxDistance) {
                self::$distance = $this->maxDistance;
            }
        }
        if (Input::filled('orderBy')) {
            $this->setOrder(Input::get('orderBy'));
        }

        // Pagination Init.
        $this->currentPage = (Input::get('page') < 0) ? 0 : (int)Input::get('page');
        $page = (Input::get('page') <= 1) ? 1 : (int)Input::get('page');
        $this->sqlCurrLimit = ($page <= 1) ? 0 : $this->perPage * ($page - 1);
    }

    /**
     * @param $sql
     * @param array $bindings
     * @return mixed
     */
    public static function query($sql, $bindings = [])
    {
        // DEBUG
        // echo 'SQL<hr><pre>' . $sql . '</pre><hr>'; //exit();
        // echo 'BINDINGS<hr><pre>'; print_r($bindings); echo '</pre><hr>';

        try {
            $result = DB::select(DB::raw($sql), $bindings);
        } catch (\Exception $e) {
            $result = null;

            // DEBUG
            // dd($e->getMessage());
        }

        return $result;
    }

    /**
     * @return array
     */
    public function fetch()
    {
        $count = $this->countPosts();
        $sql = $this->builder() . "\n" . "LIMIT " . (int)$this->sqlCurrLimit . ", " . (int)$this->perPage;

        // Count real query ads (Input::get('type') is an array in JobClass)
        $total = $count->get('all');

        // Fetch Query !
        $posts = self::query($sql, $this->bindings, 0);
        $posts = new LengthAwarePaginator($posts, $total, $this->perPage, $this->currentPage);
        $posts->setPath(Request::url());

        return ['posts' => $posts, 'count' => $count];
    }

    /**
     * @return array
     */
    public function fechAll()
    {
        if (Input::filled('q')) {
            $this->setQuery(Input::get('q'));
        }
        if (Input::filled('c')) {
            if (Input::filled('sc')) {
                $this->setCategory(Input::get('c'), Input::get('sc'));
            } else {
                $this->setCategory(Input::get('c'));
            }
        }
        if (Input::filled('r') && !empty($this->admin) && !Input::filled('l')) {
            $this->setLocationByAdminCode($this->admin->code);
        }
        if (Input::filled('l') && !empty($this->city)) {
            $this->setLocationByCityCoordinates($this->city->latitude, $this->city->longitude);
        }

        $this->setRequestFilters();

        // Execute
        return $this->fetch();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function countPosts()
    {
        // Remove the type with her SQL clause
        $where = $wherePostType = $this->arrSql->where;
        if (Input::filled('type')) {
            //unset($where['a.post_type_id']); // @todo: delete me
        }

        $sql = "SELECT count(*) as total FROM (" . $this->builder($where) . ") as x";

        // Fetch Queries !
        $all = self::query($sql, $this->bindings, 0);

        $count['all'] = (isset($all[0])) ? $all[0]->total : 0;

        return collect($count);
    }

    /**
     * @param array $where
     * @return string
     */
    private function builder($where = [])
    {
        // Set SELECT
        $this->sql->select = 'SELECT DISTINCT ' . implode(', ', $this->arrSql->select) . ', py.package_id as py_package_id';

        // Set JOIN
        $this->sql->join = '';
        if (count($this->arrSql->join) > 0) {
            $this->sql->join = "\n" . implode("\n", $this->arrSql->join);
        }

        // Set WHERE
        $where_arr = ((count($where) > 0) ? $where : $this->arrSql->where);
        $this->sql->where = '';
        if (count($where_arr) > 0) {
            foreach($where_arr as $key => $value) {
                if (is_numeric($key)) {
                    $key = '';
                }
                if ($this->sql->where == '') {
                    $this->sql->where .= "\n" . 'WHERE ' . $key . $value;
                } else {
                    $this->sql->where .= ' AND ' . $key . $value;
                }
            }
        }

        // Set GROUP BY
        $this->sql->groupBy = '';
        if (count($this->arrSql->groupBy) > 0) {
            $this->sql->groupBy = "\n" . 'GROUP BY ' . implode(', ', $this->arrSql->groupBy);
        }

        // Set HAVING
        $this->sql->having = '';
        if (count($this->arrSql->having) > 0) {
            foreach($this->arrSql->having as $key => $value) {
                if ($this->sql->having == '') {
                    $this->sql->having .= "\n" . 'HAVING ' . $key . $value;
                } else {
                    $this->sql->having .= ' AND ' . $key . $value;
                }
            }
        }

        // Set ORDER BY
        $this->sql->orderBy = '';
        $this->sql->orderBy .= "\n" . 'ORDER BY p.lft DESC';
        if (count($this->arrSql->orderBy) > 0) {
            foreach($this->arrSql->orderBy as $key => $value) {
                if ($this->sql->orderBy == '') {
                    $this->sql->orderBy .= "\n" . 'ORDER BY ' . $key . $value;
                } else {
                    $this->sql->orderBy .= ', ' . $key . $value;
                }
            }
        }

        if (count($this->arrSql->orderBy) > 0) {
            if (!in_array('a.created_at', array_keys($this->arrSql->orderBy))) {
                $this->sql->orderBy .= ', a.created_at DESC';
            }
        } else {
            if ($this->sql->orderBy == '') {
                $this->sql->orderBy .= "\n" . 'ORDER BY a.created_at DESC';
            } else {
                $this->sql->orderBy .= ', a.created_at DESC';
            }
        }

        // Set Query
        $sql = $this->sql->select . "\n" . "FROM " . table($this->table) . " as a" . $this->sql->join . $this->sql->where . $this->sql->groupBy . $this->sql->having . $this->sql->orderBy;

        return $sql;
    }

    /**
     * @param $keywords
     * @return bool
     */
    public function setQuery($keywords)
    {
        if (trim($keywords) == '') {
            return false;
        }

        // Query search SELECT array
        $select = [];

        // Get all keywords in array
        $words_tab = preg_split('/[\s,\+]+/', $keywords);

        //-- If third parameter is set as true, it will check if the column starts with the search
        //-- if then it adds relevance * 30
        //-- this ensures that relevant results will be at top
        $select[] = "(CASE WHEN a.title LIKE :keywords THEN 300 ELSE 0 END) ";
        $this->bindings['keywords'] = $keywords . '%';


        foreach($this->searchable['columns'] as $column => $relevance) {
            $tmp = [];
            foreach($words_tab as $key => $word) {
                // Skip short keywords
                if (strlen($word) <= self::$queryLength) {
                    continue;
                }
                // @todo: Find another way
                if (in_array(mb_strtolower($word), $this->banWords)) {
                    continue;
                }
                $tmp[] = $column . " LIKE :word_" . $key;
                $this->bindings['word_' . $key] = '%' . $word . '%';
            }
            if (count($tmp) > 0) {
                $select[] = "(CASE WHEN " . implode(' || ', $tmp) . " THEN " . $relevance . " ELSE 0 END) ";
            }
        }
        if (count($select) <= 0) {
            return false;
        }

        $this->arrSql->select[] = implode("+\n", $select) . "as relevance";

        // Post category relation
        if (!str_contains(implode(',', $this->arrSql->join), 'categories as c')) {
            $this->arrSql->join[] = "INNER JOIN " . table('categories') . " as c ON c.id=a.category_id AND c.active=1";
        }
        // Category parent relation
        if (!str_contains(implode(',', $this->arrSql->join), 'categories as cp')) {
            $this->arrSql->join[] = "LEFT JOIN " . table('categories') . " as cp ON cp.id=c.parent_id AND cp.active=1";
        }

        // Search with categories language
        $this->arrSql->join[] = "LEFT JOIN " . table('categories') . " as cl ON cl.translation_of=c.id AND cl.translation_lang = :translationLang";
        $this->arrSql->join[] = "LEFT JOIN " . table('categories') . " as cpl ON cpl.translation_of=cp.id AND cpl.translation_lang = :translationLang";
        $this->bindings['translationLang'] = config('lang.abbr');

        //-- Selects only the rows that have more than
        //-- the sum of all attributes relevances and divided by count of attributes
        //-- e.i. (20 + 5 + 2) / 4 = 6.75
        $average = array_sum($this->searchable['columns']) / count($this->searchable['columns']);
        $average = fixFloatVar($average);
        if ($this->forceAverage) {
            // Force average
            $average = $this->average;
        }
        $this->arrSql->having['relevance'] = ' >= :average';
        $this->bindings['average'] = $average;

        //-- Orders the results by relevance
        $this->arrSql->orderBy['relevance'] = ' DESC';
        $this->arrSql->groupBy[] = "a.id, relevance";
    }

    /**
     * @param $catId
     * @param null $subCatId
     * @return $this
     */
    public function setCategory($catId, $subCatId = null)
    {
        if (empty($catId)) {
            return $this;
        }

        // Category
        if (empty($subCatId))
        {
            if (!str_contains(implode(',', $this->arrSql->join), 'categories as c')) {
                $this->arrSql->join[] = "INNER JOIN " . table('categories') . " as c ON c.id=a.category_id AND c.active=1";
            }
            if (!str_contains(implode(',', $this->arrSql->join), 'categories as cp')) {
                $this->arrSql->join[] = "INNER JOIN " . table('categories') . " as cp ON cp.id=c.parent_id AND cp.active=1";
            }
            //$this->arrSql->where['cp.id'] = ' = :catId';
            $this->arrSql->where[':catId'] = ' IN (c.id, cp.id)';
            $this->bindings['catId'] = $catId;
        }
        // SubCategory
        else
        {
            if (!str_contains(implode(',', $this->arrSql->join), 'categories')) {
                $this->arrSql->join[] = "INNER JOIN " . table('categories') . " as c ON c.id=a.category_id AND c.active=1 AND c.translation_lang = :translationLang";
                $this->bindings['translationLang'] = config('lang.abbr');
            }
            $this->arrSql->where['a.category_id'] = ' = :subCatId';
            $this->bindings['subCatId'] = $subCatId;
        }

        return $this;
    }

    /**
     * @param $userId
     * @return $this
     */
    public function setUser($userId)
    {
        if (trim($userId) == '') {
            return $this;
        }
        $this->arrSql->where['a.user_id'] = ' = :userId';
        $this->bindings['userId'] = $userId;

        return $this;
    }

    /**
     * @param $companyName
     * @return $this
     */
    public function setCompany($companyName)
    {
        if (trim($companyName) == '') {
            return $this;
        }

        $companyName = rawurldecode($companyName);

        $this->arrSql->where['a.company_name'] = ' LIKE :companyName';
        $this->bindings['companyName'] = $companyName;

        return $this;
    }

    /**
     * Search including Administrative Division by adminCode
     *
     * @param $adminCode
     * @return $this|Search
     */
    public function setLocationByAdminCode($adminCode)
    {
        if (in_array(config('country.admin_type'), ['1', '2'])) {
            // Get the admin. division table info
            $adminType = config('country.admin_type');
            $adminTable = 'subadmin'.$adminType;
            $adminForeignKey = 'subadmin'.$adminType.'_code';

            // Query
            $this->arrSql->join[] = "INNER JOIN " . table('cities') . " as cia ON cia.id=a.city_id";
            $this->arrSql->join[] = "INNER JOIN " . table($adminTable) . " as admin ON admin.code=cia." . $adminForeignKey;
            $this->arrSql->where['admin.code'] = ' = :adminCode';
            $this->bindings['adminCode'] = $adminCode;

            return $this;
        }

        return $this;
    }

    /**
     * Search including City by City Coordinates (lat & lon)
     *
     * @param $lat
     * @param $lon
     * @return $this
     */
    public function setLocationByCityCoordinates($lat, $lon)
    {
        if ($lat == 0 || $lon == 0) {
            return $this;
        }
        $this->arrSql->orderBy['a.created_at'] = ' DESC'; // @todo:new

        // Ortho(A,B)=6371 x acos[cos(LatA) x cos(LatB) x cos(LongB-LongA)+sin(LatA) x sin(LatB)]
        $this->arrSql->select[] = '3959 * acos(cos(radians(' . $lat . ')) * cos(radians(a.lat))' . '* cos(radians(a.lon) - radians(' . $lon . '))' . '+ sin(radians(' . $lat . ')) * sin(radians(a.lat))) as distance';
        $this->arrSql->having['distance'] = ' <= :distance';
        $this->bindings['distance'] = self::$distance;
        $this->arrSql->orderBy['distance'] = ' ASC';

        return $this;
    }

    /**
     * Search including City by City Id
     *
     * @param $cityId
     * @return $this
     */
    public function setLocationByCityId($cityId)
    {
        if (trim($cityId) == '') {
            return $this;
        }
        $this->arrSql->where['a.city_id'] = ' = :cityId';
        $this->bindings['cityId'] = $cityId;

        return $this;
    }

    /**
     * @param $field
     * @return bool
     */
    public function setOrder($field)
    {
        if (!isset($this->orderMapping[$field])) {
            return false;
        }

        // Check essential field
        if ($field == 'relevance' and !str_contains($this->sql->orderBy, 'relevance')) {
            return false;
        }

        $this->arrSql->orderBy[$this->orderMapping[$field]['name']] = ' ' . $this->orderMapping[$field]['order'];
    }

    /**
     * @return $this
     */
    public function setRequestFilters()
    {
        $parameters = Request::all();
        if (count($parameters) == 0) {
            return $this;
        }

        foreach($parameters as $key => $value) {
            if (!isset($this->filters[$key])) {
                continue;
            }
            if (!is_array($value) and trim($value) == '') {
                continue;
            }

            // Special parameters
            $specParams = [];
            if ($key == 'minSalary') { // Min. Salary
                $this->arrSql->where[$this->filters[$key]] = ' >= ' . $value;
                $specParams[] = $key;
            }
            if ($key == 'maxSalary') { // Max. Salary
                $this->arrSql->where[$this->filters[$key]] = ' <= ' . $value;
                $specParams[] = $key;
            }
            if ($key == 'postedDate') { // Date
                $this->arrSql->where[$this->filters[$key]] = ' BETWEEN DATE_SUB(NOW(), INTERVAL :postedDate DAY) AND NOW()';
                $this->bindings['postedDate'] = $value;
                $specParams[] = $key;
            }

            // No-Special parameters
            if (!in_array($key, $specParams)) {
                if (is_array($value)) {
                    $tmpArr = [];
                    foreach($value as $k => $v) {
                        if (is_array($v)) continue;
                        if (!is_array($v) && trim($v) == '') continue;

                        $tmpArr[$k] = $v;
                    }
                    if (!empty($tmpArr)) {
                        $this->arrSql->where[$this->filters[$key]] = ' IN (' . implode(',', $tmpArr) . ')';
                    }
                } else {
                    $this->arrSql->where[$this->filters[$key]] = ' = ' . $value;
                }
            }
        }

        return $this;
    }
}
