<?php
/**
 * Metadatum model
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class Metadatum extends AppModel {

    public $name = 'Metadatum';

    public $belongsTo = array('Resource');

    public $whitelist = array('resource_id', 'attribute', 'value');

    /**
     * Store a piece of metadata for a resource.
     *
     * @param string $rid
     * @param string $attr
     * @param string $val
     */
    public function store($rid, $attr, $val) {
        $existing = $this->find('first', array(
            'conditions' => array(
                'resource_id' => $rid,
                'attribute' => $attr
        )));
        return $this->save(array(
            'Metadatum' => array(
                'id' => $existing ? $existing['Metadatum']['id'] : null,
                'resource_id' => $rid,
                'attribute' => $attr,
                'value' => $val
        )));
    }
}
