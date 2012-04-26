<?php
App::uses('MetaResourcesController', 'Controller');
/**
 * Jobs Controller
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
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
