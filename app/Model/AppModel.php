<?php
class AppModel extends Model {

    /**
     * Temporarily permit a field, by adding it to the whitelist.
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
    public function complete($field, $conditions=null) {
        $conditions = is_array($conditions) ? $conditions : array();
        $values = $this->find('list', array(
            'fields' => array($field),
            'conditions' => $conditions,
            'limit' => 100
        ));
        return array_unique(array_values($values));
    }

    /*
     * Convenience method for saving a model from a simple array of
     * fields. It wraps the given array in another array and sends it
     * off to save().
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
