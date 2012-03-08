<?php
class AppModel extends Model {

    public function complete($field, $conditions=null) {
        $conditions = is_array($conditions) ? $conditions : array();
        $values = $this->find('list', array(
            'fields' => array($field),
            'conditions' => $conditions,
            'limit' => 100
        ));
        return array_unique(array_values($values));
    }
}
