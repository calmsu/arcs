<?php

class Collection extends AppModel {
    public $name = 'Collection';
    public $hasMany = array('Membership');
    public $whitelist = array(
        'title', 'description', 'public', 'pdf', 'temporary'
    ); 

    /**
     * Extract member Resources from the Membership array and
     * return them.
     *
     * @param id     collection id
     * @param key    If true, a numerically indexed array of Resource
     *               objects with a mid level 'Resource' key is returned,
     *               making the array 3-deep. If false, just the outer
     *               array and bare Resource sub-arrays are returned.
     */                    
    public function getResources($id, $key=true) {
        $results = $this->find('first', array(
            'recursive' => 2,
            'conditions' => array('id' => $id),
        ));
        $resources = array();
        foreach($results['Membership'] as $member) {
            $resource = $member['Resource'];
            # If the key option is set, we'll add a mid-level keyed array.
            $push = $key ? array('Resource' => $resource) : $resource;
            array_push($resources, $push);
        }
        return $resources;
    }
}
