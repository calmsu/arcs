<?php

/**
 * Search
 *
 * Instances of the Search class build SQL for faceted search queries, given 
 * an array of facets, and returns the IDs of matching rows.
 *
 * I expect this functionality to be replaced by Apache SOLR, which is much 
 * more qualified to do this sort of thing.
 */
class Search {

    /**
     * Before we can use facets, they have be mapped to table columns. The
     * `mappings` property of the Search class holds these associations. It
     * also provides options for customizing how the facet 'works'. For example,
     * some facets map to tables that need to be joined on. Some facets should
     * be compared in a certain way (e.g. dates). These special behaviors are
     * configured in each facet's options array.
     *
     * model        provide an alias that we'll use to refer to the table. This
     *              will default to the $model property.
     *
     * field        field (or column) name that the facet corresponds to. This
     *              option is required.
     *
     * joins        an array of table => predicate pairs which will be 
     *              inner-joined, in the order that they are defined. The 
     *              predicate must be a two-member associative array following 
     *              the pattern:
     *
     *                  array(
     *                      'TableA' => 'field',
     *                      'TableB' => 'field'
     *                  )
     *
     *              where TableA is the primary table (or another previously
     *              joined), and TableB is the one being joined.
     *
     * comparison   string specifiying how the fields should be compared. By
     *              default, the equality comparison is used. Others include:
     *
     *                date - uses the DATE() operator.
     *                match - uses the MATCH() operator with IN BOOLEAN MODE.
     *                like - uses the LIKE() operator.
     *
     * values       keyed array to use as a translation table for values when 
     *              forming conditions. An example usage would be translating 
     *              strings into booleans (e.g. 'public' => true) for comparing
     *              on BOOL fields. If a translation is not found, the value will 
     *              remain unchanged.
     */
    public $MAPPINGS = array(
        'tag' => array(
            'model' => 'Tag',
            'field' => 'tag',
            'joins' => array(
                'tags' => array(
                    'Resource' => 'id', 
                    'Tag' => 'resource_id'
                )
            )
        ),
        'user' => array(
            'model' => 'User',
            'field' => 'name',
            'joins' => array(
                'users' => array(
                    'Resource' => 'user_id', 
                    'User' => 'id'
                )
            )
        ),
        'modified' => array(
            'field' => 'modified',
            'comparison' => 'date'
        ),
        'created' => array(
            'field' => 'created',
            'comparison' => 'date'
        ),
        'uploaded' => array(
            'field' => 'created',
            'comparison' => 'date'
        ),
        'comment' => array(
            'model' => 'Comment',
            'field' => 'content',
            'joins' => array(
                'comments' => array(
                    'Resource' => 'id', 
                    'Comment' => 'resource_id'
                )
            ),
            'comparison' => 'match'
        ),
        'caption' => array(
            'model' => 'Hotspot',
            'field' => 'description',
            'joins' => array(
                'hotspots' => array(
                    'Resource' => 'id', 
                    'Hotspot' => 'resource_id'
                )
            ),
            'comparison' => 'match'
        ),
        'collection' => array(
            'model' => 'Collection',
            'field' => 'title',
            'joins' => array(
                'memberships' => array(
                    'Resource' => 'id',
                    'Membership' => 'resource_id'
                ),
                'collections' => array(
                    'Membership' => 'collection_id',
                    'Collection' => 'id'
                )
            )
        ),
        'sha' => array(
            'field' => 'sha'
        ),
        'title' => array(
            'field' => 'title',
            'comparison' => 'match'
        ),
        'id' => array(
            'field' => 'id'
        ),
        'type' => array(
            'field' => 'type'
        ),
        'filetype' => array(
            'field' => 'mime_type'
        ),
        'filename' => array(
            'field' => 'file_name'
        ),
        'access' => array(
            'field' => 'public',
            'values' => array(
                'public' => true,
                'private' => false 
            )
        )
    );

    /**
     * The limit and offset properties may be reassigned on an instance any 
     * time. The change will take effect the next time a search is made.
     */
    public $LIMIT = 30;
    public $OFFSET = 0;

    /**
     * Normally, results must match all of the supplied facets--the conditions
     * are conjunctive. In some cases, this is undesirable. Set the disjunctive 
     * property to true, and conditions will be joined with 'OR'.
     */
    public $DISJUNCTIVE = false;

    public $public      = false;

    /**
     * The table and model values must be configured here.
     */
    private $TABLE      = 'resources';
    private $MODEL      = 'Resource';

    private $values     = array();
    private $joins      = array();
    private $joined     = array();
    private $conditions = array();

    /**
     * Constructor
     *
     * @param mixed query      numerically indexed facets array containing 
     *                         sub-arrays with 'category' and 'value' keys, 
     *                         or a JSON query string that will be parsed into 
     *                         facets. An example facet array is below:
     *
     *                           array(
     *                               0 => array(
     *                                   'category' => 'user',
     *                                   'value' => 'Nick Reynolds'
     *                               ),
     *                               1 => array(
     *                                   'category' => 'tag',
     *                                   'value' => 'East-Field'
     *                               )
     *                           )
     *
     *                         Facets are numerically indexed because there may
     *                         be more than one value per category.
     *
     *                         The above can also be given in string format, 
     *                         using JSON:
     *
     *                           { "user": "Nick Reynolds", "tag": "East-Field" }
     *
     *                         A query string without any facets will default 
     *                         to the special 'all' facet.
     *
     * @param array config     contains the database configuration, should contain
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
    public function __construct($config, $query=null) {

        # Extract config details.
        $this->database = $db = $config['database'];
        $host   = $config['host'];
        $driver = isset($config['driver']) ? $config['driver'] : 'mysql';
        $login  = $config['login'];
        $pass   = $config['password'];

        # Get a db connection using PDO
        $this->connection = new PDO("$driver:$host;dbname:$db", $login, $pass);

        # If given a facets array:
        if (is_array($query)) {
            $this->_addFacets($query);
        # If given a JSON object
        } else if (is_string($query)) {
            # Decode it.
            $parsed = json_decode($query, true);
            # If we were given an array:
            if (is_array($parsed)) {
                # If it's associative (i.e. 1 facet), add the one.
                if (array_key_exists('category', $parsed)) {
                    $this->addFacet($parsed['category'], $parsed['value']);
                # If it's numerically indexed, add them all.
                } else {
                    $this->_addFacets($parsed);
                }
            # If it's a string, use the special all facet.
            } else if (is_string($parsed)) {
                $this->_all($parsed);
            }
        }
    }

    /**
     * Add a facet to the Search instance.
     *
     * Facets can be added incrementally to a live instance, even after a 
     * search has already been made.
     *
     * @param string category   facet category, returns false if not in mappings.
     * @param string value      facet value
     * @return bool             true on success, false otherwise.
     */
    public function addFacet($category, $value) {

        # We can't add facets that we don't know about.
        if (!array_key_exists($category, $this->MAPPINGS)) {
            if ($category == 'text') return $this->_all($value);
            return false;
        }

        # Look up the mapping.
        $map = $this->MAPPINGS[$category];

        $model = isset($map['model']) ? $map['model'] : $this->MODEL;
        $comp = isset($map['comparison']) ? $map['comparison'] : 'equality';
        $field = $map['field'];

        # Add any joins that were given.
        if (isset($map['joins'])) {
            foreach($map['joins'] as $table => $predicate) {
                $this->_addJoin($table, $predicate);
            }
        }

        # Perform a value translation, if instructed.
        if (isset($map['values'])) {
            if (array_key_exists($value, $map['values'])) {
                $value = $map['values'][$value];
            }
        }

        return $this->_addCondition($model, $field, $value, $comp);
    }

    /**
     * Run the query and return results.
     *
     * @return array     id of each result
     */
    public function results($limit=null, $offset=null) {
        if (!is_null($limit)) {
            $this->limit = $limit;
        }
        if (!is_null($offset)) {
            $this->offset = $offset;
        }
        $sql = $this->_buildStatement();
        $rows = $this->_execute($sql, $this->values);
        return array_map(function($r) { return $r['id']; }, $rows);
    }

    /**
     * Builds and returns the SQL statement for debugging.
     *
     * @return string    SQL statement
     */
    public function getSQL() {
        return $this->_buildStatement();
    }

    public function getValues() {
        return $this->values;
    }

    /* PRIVATE METHODS */

    /**
     * Adds each facet in an array of facets.
     *
     * @param array facets   numerically indexed facets array. See above
     *                       for a better description.
     * @return bool          true if all facets were added, false otherwise.
     */
    private function _addFacets($facets) {
        $all = true;
        foreach($facets as $f) {
            if (!$this->addFacet($f['category'], $f['value'])) {
                $all = false;
            }
        }
        return $all;
    }

    private function _all($value) {
        $this->DISJUNCTIVE = true;
        foreach ($this->MAPPINGS as $facet => $map) {
            if ($facet == 'access') continue;
            if (!isset($map['comparison']) ||  $map['comparison'] == 'equality')
                $this->addFacet($facet, $value);
        }
    }

    /**
     * Adds a condition to the conditions property.
     *
     * @param string model       model (table alias) to use in the condition.     
     * @param string field       field (column) to use in the condition.
     * @param string value       value to compare with.
     * @param string comparison  comparison type
     *
     * @return mixed   Returns the condition (truthy) if one could be made and 
     *                 false otherwise.
     */
    private function _addCondition($model, $field, $value, $comparison) {
        # We're using PDO::prepare to properly escape the values. Our statement
        # string contains :[name] parameters that will be substituted by PDO.
        #
        # To name the parameters, we're using the field name, and, when used
        # more than once, a trailing integer. 
        $name = $field;
        $i = 1;
        while (array_key_exists($name, $this->values)) {
            if ($i > 1) {
                $name = substr($name, -strlen($i), strlen($i));
            }
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
                $date = DateTime::createFromFormat('m-d-Y', $value);
                $value = $date->format('Y-m-d');
                $cond = " DATE(`$model`.`$field`) = DATE(:$name)";
                break;
        }

        # Add the value and the condition. Return true.
        if (isset($cond)) {
            $this->values[$name] = $value;
            $this->conditions[] = $cond;
            return true;
        } 
        # We couldn't match the comparison.
        return false;
    }

    /**
     * Adds an INNER JOIN to the joins property, and the joining table to the
     * joined property. If the table is already in joined, we won't do another.
     *
     * @param string table
     * @param array predicate
     * @return bool True if the table was joined, false otherwise.
     */
    private function _addJoin($table, $predicate) {
        # Don't make duplicate joins. We'll keep track of the tables
        # we've joined.
        if (in_array($table, $this->joined)) {
            return false;
        }

        # Take apart the predicate array.
        $aliases = array_keys($predicate);
        $fields = array_values($predicate);
        $alias = $aliases[1];

        # Construct a join and add it to the joins array.
        $join = " INNER JOIN `{$this->database}`.`{$table}` `$alias` ";
        $join .= "ON `{$aliases[0]}`.`{$fields[0]}` = `{$aliases[1]}`.`{$fields[1]}` ";
        $this->joins[] = $join;

        # Add table to our joined array.
        $this->joined[] = $table;

        return true;
    }

    /**
     * Generates the search SQL by concatenating the instance properties
     * into a valid SQL statement.
     *
     * @return string SQL statement
     */
    private function _buildStatement() {
        $lop = $this->DISJUNCTIVE ? 'OR' : 'AND';

        $sql = "SELECT `{$this->MODEL}`.`id` FROM ";
        $sql .= "`{$this->database}`.`{$this->TABLE}` ";
        $sql .= "AS `{$this->MODEL}`";
        foreach($this->joins as $j)
            $sql .= $j;
        if ($this->conditions)
            $sql .= " WHERE " . implode(" $lop ", $this->conditions);
        if ($this->public) {
            $where = $this->conditions ? " " : " WHERE ";
            $sql .= "$where  AND  `Resource`.`public` = 1 ";
        }
        $sql .= " LIMIT {$this->LIMIT}";
        $sql .= " OFFSET {$this->OFFSET}";
        return $sql;
    }

    /**
     * Execute the given SQL with PDO::prepare using the given values.
     *
     * @return array result rows
     */
    public function _execute($sql, $values) {
        $query = $this->connection->prepare($sql, array(
            PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        ));
        $query->execute($values);
        return $query->fetchAll();
    }
}
