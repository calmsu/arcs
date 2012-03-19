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
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class MetaResourcesController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        if (!$this->request->is('ajax')) {
            # this controller only accepts ajax requests.
            $this->response->statusCode(404);
            $this->redirect('/404');
        }
        $this->Auth->allow('view');
    }

    /**
     * Add a meta-resource
     */
    public function add() {
        $model = $this->modelClass;
        if ($this->request->data) {
            $data = array($model => $this->request->data);
            # Temporarily whitelist the user_id field.
            $this->$model->permit('user_id');
            $data[$model]['user_id'] = $this->Auth->user('id');
            if ($this->$model->save($data)) {
                return $this->jsonResponse(201);
            } 
        } 
        # jsonResponse is defined in AppController
        return $this->jsonResponse(400);
    }

    /**
     * View a meta-resource
     *
     * @param id
     */
    public function view($id) {
        $model = $this->modelClass;
        $result = $this->$model->findById($id);
        if (!$result) {
            return $this->jsonResponse(404);
        }
        return $this->jsonResponse(200, $result);
    }

    /**
     * Delete a meta-resource
     *
     * @param id
     */
    public function delete($id) {
        $model = $this->modelClass;
        if ($this->$model->delete($id)) {
            return $this->jsonResponse(204);
        }
        return $this->jsonResponse(500);
    }
}
