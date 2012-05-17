<?php
/**
 * App model
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class AppModel extends Model {

    /**
     * When `flatten` is true, we'll flatten results to their bare model
     * fields. So instead of this:
     *
     *   array(
     *      'Post' => array(
     *          'id' => 1,
     *          'title' => 'Some title'
     *      )
     *   )
     *
     * ...you get this:
     *
     *   array(
     *      'id' => 1,
     *      'title' => 'Some title'
     *   )
     *
     * This should be used in conjunction with the `recursive` property.
     */
    public $flatten = false;

    /**
     * Flatten results. When the `afterFind` method is defined in extending
     * models, be sure to include:
     *
     *  $results = parent::afterFind($results, $primary);
     *
     * ...if you want to keep the flatten functionality.
     */
    public function afterFind($results, $primary) {
        if ($this->flatten) {
            if (isset($results[$this->name]))
                return $results[$this->name];
            if (isset($results[0][$this->name])) {
                $flattened = array();
                foreach($results as $r) {
                    $flattened[] = $r[$this->name];
                }
                return $flattened;
            }
        }
        return $results;
    }

    /**
     * Temporarily permit a field for saving, by adding it to the whitelist.
     *
     * @param string $field   one or more fields as arguments.
     * @return void
     */
    public function permit($field) {
        $fields = func_get_args();
        foreach ($fields as $f) {
            $this->whitelist[] = $f;
        }
    }

    /**
     * Complement to `permit`. Temporarily forbid a field from saving.
     *
     * @param string $field    one or more fields as arguments.
     * @return void
     */
    public function forbid($field) {
        $fields = func_get_args();
        foreach ($fields as $f) unset($this->whitelist[$f]);
    }

    /**
     * Convenience method for calling Model->read, Model->set and Model->save
     * a little more concisely.
     *
     * @param string $id
     * @param array $fields
     */
    public function saveById($id, $fields) {
        if (!$this->read(null, $id)) return false;
        $this->set($fields);
        $this->save();
    }
    
    /**
     * Convenience method for generating autocompletion arrays.
     *
     * @param string $field      e.g. Resource.title
     * @param array $conditions  an array of conditions that will be passed 
     *                           along to the find method.
     * @param bool $date         group by a date field's day.
     */
    public function complete($field, $conditions=array(), $date=false) {
        $_flatten = $this->flatten;
        $this->flatten = false;
        $values = $this->find('list', array(
            'fields' => $field,
            'conditions' => $conditions,
            'group' => $date ? "DAY($field)" : $field,
            'limit' => 100
        ));
        $this->flatten = $_flatten;
        return array_values($values);
    }

    /**
     * Convenience method for saving a model without needing to wrap it
     * in an outer HABTM array, as in:
     *
     *   Model::save(array('Model' => array('title' => 'Some Title')))
     *
     * Instead, just do:
     *
     *   Model::add(array('title' => 'Some Title'))
     *
     * @param array $data
     */
    public function add($data) {
        return $this->save(array($this->name => $data));
    }

    /**
     * Returns results where Model.id is in $ids. The important bit is that it 
     * will also return the results in the order of the given ids, by using 
     * MySQL's FIELD() function. We use this to maintain SOLR's relevance 
     * sorting.
     *
     * @param array $ids
     */
    public function findAllFromIds($ids) {
        $order = sprintf("FIELD(%s.id, %s) DESC",
            $this->name,
            implode(', ', array_map(function($id) { return "'$id'"; }, $ids))
        );
        return $this->find('all', array(
            'conditions' => array("{$this->name}.id" => $ids),
            'order' => $order
        ));
    }

    /**
     * This is a utility method for reformatting the results arrays that are
     * returned by the afterFind callback. The results can take a few different
     * formats. This method will normalize the results and provide them in a
     * consistent format to a callback function.
     *
     * @param array $results   the results parameter of the afterFind method. 
     *                         Should be an array, of one of three formats:
     *
     *                           array(...)
     *                           array('Model' => ...)
     *                           array(0 => 'Model' => ...)
     *                  
     * @param function $func   an anonymous function or other callable. We'll 
     *                         pass it the normalized result and use the return 
     *                         value to reset the result.
     * @param object $context  pass an object (such as $this) in, and you can 
     *                         use it within the function, given as the second 
     *                         parameter to your callable.
     */
    public function resultsMap($results, $func, $context=null) {
        if (isset($results[0][$this->name])) {
            foreach($results as $k => $v) {
                $results[$k][$this->name] = $func($v[$this->name], $context);
            }
        } else if (isset($results[0])) {
            foreach($results as $k => $v) {
                $results[$k] = $func($v, $context);
            }
        } else if (isset($results[$this->name])) {
            $results[$this->name] = $func($results[$this->name], $context);
        } else {
            $results = $func($results, $context);
        }
        return $results;
    }
}
