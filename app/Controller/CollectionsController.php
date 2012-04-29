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
        $this->Auth->allow('view', 'viewer', 'create', 'complete');
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
     * 
     * POST the collection data and optionally an array of member
     * resource ids.
     */
    public function create() {
        if ($this->request->is('post') && $this->data) {
            # Save the collection.
            $data = array('Collection' => $this->data);
            $this->Collection->permit('user_id');
            $data['Collection']['user_id'] = $this->Auth->user('id');
            $this->Collection->save($data);

            # The 'Members' key may be given as a list of resource 
            # id's that will be saved as Memberships.
            if ($this->data['members']) {
                $members = array();
                foreach ($this->data['members'] as $member) {
                    array_push($members, array(
                        'collection_id' => $this->Collection->id,
                        'resource_id' => $member
                    ));
                }
                $this->Collection->Membership->saveMany($members);
            }
            if ($this->request->is('ajax')) {
                $this->json(201, array('id' => $this->Collection->id));
            } else {
                $this->redirect('/collection/' . $this->Collection->id);
            }
        }
    }

    /**
     * Update the collection.
     *
     * @param id
     */
    public function update($id=null) {
    }

    /**
     * View a collection.
     *
     * @param id
     */
    public function viewer($id=null) {
        $collection = $this->Collection->findById($id);
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
     * @param id
     */
    public function delete($id=null) {
        if (!$this->request->is('delete')) throw new MethodNotAllowedException();
        if (!$this->Access->isAdmin()) throw new ForbiddenException();
        if (!$this->Collection->delete($id)) throw new NotFoundException();
        $this->json(204);
    }

    public function complete() {
        if ($this->request->is('ajax')) {
            return $this->json(200, $this->Collection->complete(
                'Collection.title', array(
                    'Collection.title !=' => 'Temporary Collection'
                )
            ));
        }
    }
}
