<?php
/**
 * Membership model
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class Membership extends AppModel {
    public $name = 'Membership';
    public $belongsTo = array(
        'Resource', 'Collection'
    );
    public $whitelist = array('resource_id', 'collection_id', 'page');

    /**
     * Convenience method for connecting a resource to a collection
     * through a membership.
     *
     * @param rid    resource id
     * @param cid    collection id
     */
    public function pair($rid, $cid, $page=null) {
        if (!$page)
            $page = $this->find('count', array(
                'conditions' => array(
                    'Membership.collection_id' => $cid
            ))) + 1;
        return $this->add(array(
            'resource_id' => $rid,
            'collection_id' => $cid,
            'page' => $page
        ));
    }
}
