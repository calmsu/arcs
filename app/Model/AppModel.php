<?php
class AppModel extends Model {

    /**
     * Whitelist of fields. By default, everything is whitelisted, extending 
     * models should override this behavior.
     */
    public $whitelist = array('*');

    /**
     * Overrides the save method to automatically remove non-whitelisted fields
     * from the data array.
     *
     * The goal here is to prevent mass assignment of fields that should not be
     * user-defineable (e.g. id). The Security component provides this, but only
     * through the Form Helper, which we don't often use.
     *
     * @param data
     * @param validate
     * @param fieldList
     */
    public function save($data=null, $validate=true, $fieldList=array()) {
        debug($data);
        if (is_array($data)) $data = $this->_filterWhitelist($data);
        debug($data);
        return parent::save($data, $validate, $fieldList);
    }

    /**
     * Convieniece method for temporarily whitelisting a field.
     *
     * It's necessary to call this if, for example, a controller action will
     * set a field that the user may not (such as `user_id`). The field should
     * not be added to the whitelist, but instead be temporarily permitted.
     *
     * @param fields    string or array of strings to whitelist.
     */
    public function permit($fields) {
        if (!is_array($fields)) $fields = array($fields);
        $this->_whitelist = $this->whitelist;
        $this->whitelist = array_merge($this->whitelist, $fields);
    }

    /**
     * Removes keys that are not present in the whitelist property from the
     * save-formatted data array.
     *
     * @param data
     */
    private function _filterWhitelist($data) {
        if ($this->whitelist[0] == '*') return $data;
        if (!isset($data[$this->name])) return $data;

        foreach ($data[$this->name] as $field => $value) {
            if (!in_array($field, $this->whitelist)) 
                unset($data[$this->name][$field]);
        }
        return $data;
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
        return $this->save(array(
            $this->name => $data
        ));
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
