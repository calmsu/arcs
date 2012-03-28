<?php
/**
 * MetaResources controller.
 *
 * This controller will only respond to ajax requests. 
 *
 * The MetaResource controller is extended in ARCS to provide the common 
 * functionality needed by the meta-resources (e.g. Comments, Tags, Hotspots).
 *
 * By default, the Auth component is configured to block unauthenticated 
 * requests to the add and delete actions. The view action is, however, allowed.
 * This behavior may be overriden by a sub-class in the beforeFilter, just be 
 * sure to call the parent beforeFilter first.
 *
 * @package      ARCS
 * @link         http://github.com/calmsu/arcs
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 * @license
 */
class MetaResourcesController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        # This controller only accepts ajax requests.
        if (!$this->request->is('ajax')) return $this->redirect('/404');
        $this->Auth->allow('view');
    }

    /**
     * Add a meta-resource
     */
    public function add() {
        $model = $this->modelClass;
        if (!$this->request->data) return $this->json(400);
        # Temporarily whitelist the user_id field.
        $this->$model->permit('user_id');
        $this->request->data['user_id'] = $this->Auth->user('id');
        if (!$this->$model->add($this->request->data)) return $this->json(500);
        $this->json(201);
    }

    /**
     * View a meta-resource
     *
     * @param string $id
     */
    public function view($id) {
        $model = $this->modelClass;
        $result = $this->$model->findById($id);
        if (!$result) return $this->json(404);
        $this->json(200, $result);
    }

    /**
     * Delete a meta-resource
     *
     * @param string $id
     */
    public function delete($id) {
        $model = $this->modelClass;
        if (!$this->$model->exists($id)) return $this->json(404);
        if (!$this->$model->delete($id)) return $this->json(500);
        $this->json(204);
    }
}
