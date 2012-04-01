<?php
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
     * @param  field  one or more fields as arguments.
     * @return void
     */
    public function permit($field) {
        $fields = func_get_args();
        foreach ($fields as $f) {
            $this->whitelist[] = $f;
        }
    }

    /**
     * Convenience method for generating autocompletion arrays.
     *
     * @param field       e.g. Resource.title
     * @param conditions  an array of conditions that will be passed along
     *                    to the find method.
     */
    public function complete($field, $conditions=array()) {
        $values = $this->find('list', array(
            'fields' => array($field),
            'conditions' => $conditions,
            'limit' => 100
        ));
        return array_unique(array_values($values));
    }

    /*
     * Convenience method for saving a model without needing to wrap it
     * in an outer HABTM array, as in:
     *
     *   Model::save(array('Model' => array('title' => 'Some Title')))
     *
     * Instead, just do:
     *
     *   Model::add(array('title' => 'Some Title'))
     *
     */
    public function add($data) {
        return $this->save(array($this->name => $data));
    }

    /*
     * This is a utility method for reformatting the results arrays that are
     * returned by the afterFind callback. The results can take a few different
     * formats. This method will normalize the results and provide them in a
     * consistent format to a callback function.
     *
     * @param results   the results parameter of the afterFind method. Should be
     *                  an array, of one of three formats:
     *
     *                      array(...)
     *                      array('Model' => ...)
     *                      array(0 => 'Model' => ...)
     *                  
     * @param func      an anonymous function or other callable. We'll pass it
     *                  the normalized result and use the return value to reset
     *                  the result.
     * @param context   pass an object (such as $this) in, and you can use it 
     *                  within the function, through the second parameter.
     */
    public function resultsMap($results, $func, $context=null) {
        if (isset($results[0][$this->name])) {
            foreach($results as $k => $v) {
                $results[$k][$this->name] = $func($v[$this->name], $context);
            }
        } else if (isset($results[$this->name])) {
            $results[$this->name] = $func($results[$this->name], $context);
        } else {
            $results = $func($results, $context);
        }
        return $results;
    }
}
