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
        $this->Auth->allow('viewer', 'create', 'complete');
    }

    /**
     * Display all collections.
     */
    public function index() {
        $this->set('collections', $this->Collection->find('all'));
        $this->set('_serialize', 'collections');
    }

    /**
     * Create a new collection.
     */
    public function create() {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();
        if (!$this->request->data) throw new BadRequestException();
        # Save the collection.
        $this->request->data['user_id'] = $this->Auth->user('id');
        $this->Collection->permit('user_id');
        $this->Collection->add($this->request->data);
        # The 'Members' key may be given as a list of resource 
        # id's that will be saved as Memberships.
        if ($this->request->data['members']) {
            $members = array_map(function($m) { 
                return array(
                    'collection_id' => $this->Collection->id,
                    'resource_id' => $m
                );
            }, $this->request->data['members']);
            $this->Collection->Membership->saveMany($members);
        }
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
