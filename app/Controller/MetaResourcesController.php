<?php
/**
 * MetaResources controller.
 *
 * The MetaResource controller is extended in ARCS and used as a template for
 * RESTful actions. It doesn't use views--all data is sent and received as
 * JSON.
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
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
        if (!$this->request->data) throw new NotFoundException;
        # Temporarily whitelist the user_id field.
        $this->$model->permit('user_id');
        $this->request->data['user_id'] = $this->Auth->user('id');
        if (!$this->$model->add($this->request->data)) 
            throw new InternalErrorException();
        $this->json(201, $this->$model->findById($this->$model->id));
    }

    /**
     * Edit a meta-resource
     */
    public function edit($id) {
        $model = $this->modelClass;
        $this->$model->read(null, $id);
        if (!$this->$model->exists()) throw new NotFoundException();
        if (!$this->request->data) throw new BadRequestException();
        if (!($this->request->is('put') || $this->request->is('post'))) 
            throw new MethodNotAllowedException();
        if (!$this->$model->add($this->request->data)) 
            throw new InternalErrorException();
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
        if (!$result) throw new NotFoundException(); 
        $this->json(200, $result);
    }

    /**
     * Delete a meta-resource
     *
     * @param string $id
     */
    public function delete($id) {
        $model = $this->modelClass;
        $this->$model->flatten = true;
        $result = $this->$model->findById($id);
        if (!$result) throw new NotFoundException();
        if (!($this->Access->isSrResearcher() || $this->Access->isCreator($result)))
            throw new ForbiddenException();
        if (!$this->$model->delete($id)) throw new InternalErrorException();
        $this->json(204);
    }
}
