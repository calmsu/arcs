<?php
/**
 * MetaResources controller.
 *
 * The MetaResource controller is extended in ARCS and used as a template for
 * RESTful actions. It doesn't use views--all data is sent and received as
 * JSON.
 *
 * @package      ARCS
 * @link         http://github.com/calmsu/arcs
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 * @license
 */
class MetaResourcesController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('view');
        $model = $this->modelClass;
        if (!isset($this->request->query['related'])) {
            $this->$model->recursive = -1;
            $this->$model->flatten = true;
        }
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
     * Edit a meta-resource
     */
    public function edit($id) {
        $model = $this->modelClass;
        $this->Model->read(null, $id);
        if (!$this->$model->exists()) return $this->json(404);
        if (!$this->request->data) return $this->json(400);
        if (!($this->request->is('put') || $this->request->is('post'))) 
            return $this->json(405);
        if (!$this->$model->add($this->request->data)) return $this->json(500);
        $this->json(200);
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
