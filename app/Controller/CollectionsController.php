<?php
/**
 * Collections Controller
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class CollectionsController extends AppController {
    public $name = 'Collections';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('viewer', 'index', 'create', 'complete');
    }

    /**
     * Display all collections.
     */
    public function index() {
        $this->Collection->recursive = -1;
        $this->set('collections', $this->Collection->find('all', array(
            'order' => 'Collection.modified DESC'
        )));
        if ($this->Auth->loggedIn())
            $this->set('user_collections', $this->Collection->find('all', array(
                'conditions' => array('Collection.user_id' => $this->Auth->user('id')),
                'order' => 'Collection.modified DESC'
            )));
    }

    /**
     * Create a new collection.
     */
    public function add() {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();
        if (!$this->request->data) throw new BadRequestException();
        # Save the collection.
        $this->request->data['user_id'] = $this->Auth->user('id');
        $this->Collection->permit('user_id');
        $this->Collection->add($this->request->data);
        # The 'Members' key may be given as a list of resource 
        # id's that will be saved as Memberships.
        if ($this->request->data['members']) {
            $ci = $this;
            $members = array_map(function($m) use ($ci) { 
                return array(
                    'collection_id' => $ci->Collection->id,
                    'resource_id' => $m
                );
            }, $this->request->data['members']);
            $this->Collection->Membership->saveMany($members);
        }
        $this->Collection->flatten = true;
        $this->json(201, $this->Collection->findById($this->Collection->id));
    }

    /**
     * Update the collection.
     *
     * @param string $id
     */
    public function update($id=null) {
        $this->json(501);
    }

    /**
     * Delete a collection.
     *
     * @param string id
     */
    public function delete($id=null) {
        if (!$this->request->is('delete')) throw new MethodNotAllowedException();
        if (!$this->Access->isAdmin()) throw new ForbiddenException();
        if (!$this->Collection->delete($id)) throw new NotFoundException();
        $this->json(204);
    }

    /**
     * Add members to an existing collection.
     */
    public function append($id) {
        $collection = $this->Collection->findById($id);
        if (!$collection) throw new NotFoundException();
        if (!is_array($this->request->data['members']))
            throw new BadRequestException();
        foreach($this->request->data['members'] as $m) {
            $this->Collection->Membership->pair($m, $id);
            $this->Collection->Membership->create();
        }
        $this->json(201);
    }

    /**
     * View a collection.
     *
     * @param string $id
     */
    public function viewer($id=null) {
        if (!$id) throw new BadRequestException();
        $collection = $this->Collection->findById($id);
        if (!$collection) throw new NotFoundException();
        $this->set('title_for_layout', $collection['Collection']['title']);
        $this->set('body_class', 'viewer');
        $this->set('footer', false);
        $this->loadModel('Resource');
        $rids = $this->Collection->Membership->members($id);
        $this->set('resources', $this->Resource->find('all', array(
            'conditions' => array(
                'Resource.id' => $rids
        ))));
        $this->set('collection', $collection['Collection']);
        $this->set('toolbar', array(
            'actions' => true,
            'logo' => true
        ));
    }

    /**
     * Return an array of (id, title) pairs. Similar to `complete`, but includes
     * the ids.
     */
    public function titles() {
        if (!$this->request->is('get')) throw new MethodNotAllowedException();
        return $this->json(200, $this->Collection->find('list', array(
            'fields' => 'Collection.title'
        )));
    }

    /**
     * Complete collection titles.
     */
    public function complete() {
        if (!$this->request->is('get')) throw new MethodNotAllowedException();
        return $this->json(200, $this->Collection->complete(
            'Collection.title', array(
                'Collection.title !=' => 'Temporary Collection'
            )
        ));
    }
}
