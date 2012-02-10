<?php
/**
 * Memberships Controller
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class MembershipsController extends AppController {
    public $name = 'Memberships';

    public function add($collection_id, $resource_id) {
        if ($this->request->is('ajax')) {
            $this->Membership->save(array(
                'Membership' => array(
                    'collection_id' => $collection_id,
                    'resource_id' => $resource_id
            )));
            $this->autoRender = false;
        }
        else {
            $this->redirect('/404');
        }
    }

    public function index() {
        $this->set('memberships', $this->Membership->find('all'));
        $this->set('_serialized', 'memberships');
    }
}
