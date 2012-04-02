<?php
App::uses('MetaResourcesController', 'Controller');
/**
 * Collections Controller
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class TasksController extends MetaResourcesController {
    public $name = 'Tasks';

    public function beforeFilter() {
        parent::beforeFilter();
        if (!$this->Auth->user('role') === 0) $this->redirect('/');
    }

    public function index() {
        return $this->json(200, $this->Task->find('all', array(
            'order' => 'Task.created DESC'
        )));
    }
}
