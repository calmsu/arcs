<?php

namespace Arcs;

require_once('Utilities.php');
require_once('Search.php');

/**
 * SqlSearch
 *
 * Generates SQL for facet searches. 
 */
class SqlSearch extends Search {

    /**
     * Configure the result set.
     */
    public $options = array(
        'limit' => 30,
        'offset' => 0,
        'order' => 'modified',
        'direction' => 'DESC'
    );

    /**
     * Show only public resources.
     */
    public $publicFilter = true;

    /**
     * The table and model values must be configured here.
     */
    protected $table         = 'resources';
    protected $model         = 'Resource';

    protected $joins         = array();
    protected $joined        = array();

    protected $values        = array();

    protected $andConditions = array();
    protected $orConditions  = array();

    /**
     * Constructor
     *
     * @param array $config    contains the database configuration, should contain
     *                         the following keys:
     *
     *                           host     - database hostname (e.g. localhost)
     *                           driver   - available PDO db driver (e.g. mysql) 
     *                           login    - database login
     *                           password - database login password
     *                           database - name of the database
     *
     *                         Because this is intended to work with ARCS, this
     *                         is based on CakePHP's DboSource config array.
     */
    public function __construct($config) {

        # Extract config details.
        $this->database = $db = $config['database'];
        $host   = $config['host'];
        $driver = isset($config['driver']) ? $config['driver'] : 'mysql';
        $login  = $config['login'];
        $pass   = $config['password'];

        # Get a db connection using PDO
        $this->connection = new \PDO("$driver:$host;dbname:$db", $login, $pass);
    }

    /**
     * Run the query and return results.
     *
     * @return array     id of each result
     */
    public function search($query=null, $options=array()) {
        $options = array_merge($this->options, $options);
        if (is_array($query)) $this->addFacets($query);
        $sql = $this->buildStatement('results', $options);
        $count_sql = $this->buildCountStatement();
        $rows = $this->execute($sql, $this->values);
        $count = $this->execute($count_sql, $this->values);
        return array(
            'total' => $count[0][0],
            'limit' => $options['limit'],
            'offset' => $options['offset'],
            'order' => $options['order'],
            'direction' => $options['direction'],
            'mode' => 'sql',
            'query' => $query,
            'raw_query' => $sql,
            'results' => \_\pluck($rows, 'id'),
            'num_results' => count($rows)
        );
    }

    /**
     * Run the query and return a completion array for a category.
     *
     * @return array     completion values
     */
    public function complete($category, $query=null, $options=array()) {
        if (is_array($query)) $this->addFacets($query);
        $map = $this->mappings[$category];
        $sql = $this->buildCompleteStatement($category, $options);
        $rows = $this->execute($sql, $this->values);
        return array_unique(array_values(array_filter(\_\pluck($rows, $map['field']))));
    }

    /**
     * Parse category and value pairs from a query string.
     *
     * @param string $query    e.g. 'title: "East Field"' or 'East Field'
     * @return array           parsed query array
     */
    public function parseQuery($query) {
        $array = array();

        preg_match_all('/(\w+):/', $query, $categories);
        preg_match_all('/"([^"]+)"/', $query, $values);

        $categories = array_pop($categories);
        $values = array_pop($values);

        foreach($categories as $i=>$cat)
            array_push($array, array('category' => $cat, 'value' => $values[$i]));

        if (empty($categories) && strlen($query) > 0)
            $array[] = array('category' => 'text', 'value' => $query);

        return $array;
    }

    /**
     * Get the fully qualified database field name for a category
     *
     * @param string $category    collection
     * @return string             Collection.title
     */
    public function getCategorySpecifier($category) {
        if (isset($this->mappings[$category])) {
            $map = $this->mappings[$category];
            $model = isset($map['model']) ? $map['model'] : 'Resource';
            return $model . '.' . $map['field'];
        }
        return null;
    }

    /**
     * Add a facet to the Search instance.
     *
     * @param string $category   facet category, returns false if not in mappings.
     * @param string $value      facet value
     * @return bool             true on success, false otherwise.
     */
    public function addFacet($category, $value, $type = 'and') {

        # We can't add facets that we don't know about.
        if (!array_key_exists($category, $this->mappings)) {
            if ($category == 'text') return $this->addAllFacet($value);
            return false;
        }

        # Look up the mapping.
        $map = $this->mappings[$category];

        $model = isset($map['model']) ? $map['model'] : $this->model;
        $comp = isset($map['comparison']) ? $map['comparison'] : 'equality';
        $field = $map['field'];

        # Add any joins that were given.
        if (isset($map['joins'])) {
            foreach($map['joins'] as $table => $predicate) {
                $this->addJoin($table, $predicate);
            }
        }

        # Perform a value translation, if instructed.
        if (isset($map['values'])) {
            if (array_key_exists($value, $map['values'])) {
                $value = $map['values'][$value];
            }
        }

        return $this->addCondition($model, $field, $value, $comp, $type);
    }

    /**
     * Adds each facet in an array of facets.
     *
     * @param array $facets  numerically indexed facets array. See above
     *                       for a better description.
     * @return bool          true if all facets were added, false otherwise.
     */
    private function addFacets($facets, $type = 'and') {
        $all = true;
        foreach($facets as $f) {
            if (!$this->addFacet($f['category'], $f['value'], $type)) {
                $all = false;
            }
        }
        return $all;
    }

    /**
     * Adds the special 'text' facet, which searches all comparable fields.
     *
     * @param string $value    comparison value
     */
    private function addAllFacet($value) {
        foreach ($this->mappings as $facet => $map) {
            if ($facet == 'access') continue;
            if (!isset($map['comparison']) ||  $map['comparison'] == 'equality')
                $this->addFacet($facet, $value, 'or');
        }
    }

    public function getValues() {
        return $this->values;
    }

    /**
     * Adds a condition to the conditions property.
     *
     * @param string $model       model (table alias) to use in the condition.     
     * @param string $field       field (column) to use in the condition.
     * @param string $value       value to compare with.
     * @param string $comparison  comparison type
     * @param string $type        'and' or 'or'
     *
     * @return mixed   Returns the condition (truthy) if one could be made and 
     *                 false otherwise.
     */
    private function addCondition($model, $field, $value, $comparison, $type) {
        # We're using PDO::prepare to properly escape the values. Our statement
        # string contains :[name] parameters that will be substituted by PDO.
        #
        # To name the parameters, we're using the field name, and, when used
        # more than once, a trailing integer. 
        $name = $field;
        $i = 1;
        while (array_key_exists($name, $this->values)) {
            if ($i > 1) $name = substr($name, 0, -strlen($i));
            $name .= $i;
            $i++;
        }

        # Use the specified comparison template.
        switch ($comparison) {
            case "equality":
                $cond = " `$model`.`$field` = :$name";
                break;
            case "match":
                $cond = " MATCH(`$model`.`$field`) AGAINST(:$name IN BOOLEAN MODE)";
                break;
            case "like":
                $cond = " `$model`.`$field` = ':$name'";
                break;
            case "date":
                $date = \DateTime::createFromFormat('d-m-Y', $value);
                $value = $date->format('Y-m-d');
                $cond = " DATE(`$model`.`$field`) = DATE(:$name)";
                break;
        }

        # Add the value and the condition. Return true.
        if (isset($cond)) {
            if ($type == 'or')
                $this->orConditions[] = $cond;
            else
                $this->andConditions[] = $cond;
            $this->values[$name] = $value;
            return true;
        } 
        # We couldn't match the comparison.
        return false;
    }

    /**
     * Adds an INNER JOIN to the joins property, and the joining table to the
     * joined property. If the table is already in joined, we won't do another.
     *
     * @param string $table
     * @param array $predicate
     * @return bool True if the table was joined, false otherwise.
     */
    private function addJoin($table, $predicate) {
        # Don't make duplicate joins. We'll keep track of the tables
        # we've joined.
        if (in_array($table, $this->joined)) {
            return false;
        }

        # Take apart the predicate array.
        $aliases = array_keys($predicate);
        $fields = array_values($predicate);
        $alias = $aliases[1];
        $joinType = isset($predicate['type']) ? $predicate['type'] : 'LEFT OUTER JOIN';

        # Construct a join and add it to the joins array.
        $join = " $joinType `{$this->database}`.`{$table}` `$alias` ";
        $join .= "ON `{$aliases[0]}`.`{$fields[0]}` = `{$aliases[1]}`.`{$fields[1]}` ";
        $this->joins[] = $join;

        # Add table to our joined array.
        $this->joined[] = $table;

        return true;
    }

    private function buildCountStatement($options=array()) {
        return $this->buildStatement('count', $options);
    }

    private function buildCompleteStatement($category, $options=array()) {
        $map = $this->mappings[$category];
        $options['completionField'] = $this->getCategorySpecifier($category);
        if (isset($map['joins'])) {
            foreach($map['joins'] as $table => $predicate) {
                $this->addJoin($table, $predicate);
            }
        }
        return $this->buildStatement('complete', $options);
    }

    /**
     * Generates the search SQL by concatenating the instance properties
     * into a valid SQL statement.
     *
     * @return string SQL statement
     */
    private function buildStatement($type='results', $options=array()) {
        $options = array_merge($this->options, $options);

        if ($type == 'count')
            $sql = "SELECT COUNT(`{$this->model}`.`id`) FROM ";
        if ($type == 'results')
            $sql = "SELECT `{$this->model}`.`id` FROM ";
        if ($type == 'complete')
            $sql = "SELECT {$options['completionField']} FROM ";

        $sql .= "`{$this->database}`.`{$this->table}` ";
        $sql .= "AS `{$this->model}`";

        foreach($this->joins as $j)
            $sql .= $j;

        if ($this->andConditions || $this->orConditions) {
            $sql .= " WHERE ";
            if ($this->andConditions)
                $sql .= "(" . implode(" AND ", $this->andConditions) . ") ";
            if ($this->andConditions && $this->orConditions)
                $sql .= " AND ";
            if ($this->orConditions)
                $sql .= "(" . implode(" OR ", $this->orConditions) . ") ";
        }

        if ($this->publicFilter) {
            $where = strpos($sql, "WHERE") > 1 ? " " : " WHERE ";
            $sql .= "$where  AND  `Resource`.`public` = 1 ";
        }

        if ($type == 'count') return $sql;

        $sql .= " ORDER BY `{$this->model}`.`{$options['order']}` {$options['direction']}";
        $sql .= " LIMIT {$options['limit']}";
        $sql .= " OFFSET {$options['offset']}";

        return $sql;
    }

    /**
     * Execute the given SQL with PDO::prepare using the given values.
     *
     * @return array result rows
     */
    public function execute($sql, $values) {
        $query = $this->connection->prepare($sql, array(
            \PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY
        ));
        $query->execute($values);
        return $query->fetchAll();
    }
}
