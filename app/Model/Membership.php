<?php

class Membership extends AppModel {
    public $name = 'Membership';
    public $belongsTo = array(
        'Resource', 'Collection'
    );
    public $whitelist = array('resource_id', 'collection_id');

    /**
     * Convenience method for connecting a resource to a collection
     * through a membership.
     *
     * @param rid    resource id
     * @param cid    collection id
     */
    public function pair($rid, $cid) {
        return $this->add(array(
            'resource_id' => $rid,
            'collection_id' => $cid
        ));
    }
}
