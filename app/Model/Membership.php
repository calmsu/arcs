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
     * @param string $rid    resource id
     * @param string $cid    collection id
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

    /**
     * Get the ids of collections to which the resource is a member.
     *
     * @param string $rid   resource id
     */
    public function memberships($rid) {
        return $this->find('list', array(
            'fields' => 'Membership.collection_id',
            'conditions' => array(
                'resource_id' => $rid
            )
        ));
    }

    /**
     * Get the ids of member resources of a collection.
     *
     * @param string $cid   collection id
     */
    public function members($cid) {
        return $this->find('list', array(
            'fields' => 'Membership.resource_id',
            'conditions' => array(
                'collection_id' => $cid
            ),
            'order' => 'Membership.page'
        ));
    }
}
