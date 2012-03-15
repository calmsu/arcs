<?php

class Membership extends AppModel {
    public $name = 'Membership';
    public $belongsTo = array(
        'Resource', 'Collection'
    );

    /**
     * Convenience method for connecting a resource to a collection
     * through a membership.
     *
     * @param rid    resource id
     * @param cid    collection id
     */
    public function pair($rid, $cid) {
        return $this->save(array(
            'Membership' => array(
                'resource_id' => $this->Resource->id,
                'collection_id' => $collection_id
            )
        ));
    }
}
