<?php
App::uses('MetaResourcesController', 'Controller');
/**
 * Collections Controller
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class JobsController extends MetaResourcesController {
    public $name = 'Jobs';

    public function beforeFilter() {
        parent::beforeFilter();
        if (!$this->Access->isAdmin()) $this->redirect('/');
    }

    public function index() {
        return $this->json(200, $this->Job->find('all', array(
            'order' => 'Job.created DESC'
        )));
    }
}
