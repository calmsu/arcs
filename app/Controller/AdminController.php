<?php
App::uses('ConnectionManager', 'Model');
/**
 * Admin controller.
 *
 * This is largely a read-only group of views. Admin actions are carried out 
 * through ajax requests to the proper controller actions on the client-side.
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class AdminController extends AppController {
    public $name = 'Admin';
    public $uses = array('User', 'Job');

    public function beforeFilter() {
        parent::beforeFilter();
        if (!$this->Access->isAdmin()) {
            $this->Session->setFlash('You must be an Admin to access the Admin ' .
               ' console.', 'flash_error');
            $this->redirect('/');
        }
    }

    /**
     * Displays information about the system configuration.
     */
    public function status() {
        $this->set('core', array(
            'debug' => Configure::read('debug'),
            'database' => @ConnectionManager::getDataSource('default')
        ));
        $uploads_path = Configure::read('uploads.path');
        $this->set('uploads', array(
            'url' => Configure::read('uploads.url'),
            'path' => $uploads_path,
            'exists' => is_dir($uploads_path),
            'writable' => is_writable($uploads_path),
            'executable' => is_executable($uploads_path)
        ));
        $this->set('dependencies', array(
            'Ghostscript' => is_executable('ghostscript'),
            'Imagemagick' => class_exists('Imagick')
        ));
    }

    /**
     * Displays the error and debug logs.
     */
    public function logs() {
        $this->set(array(
            'error'  => @file_get_contents(LOGS . 'error.log'),
            'debug'  => @file_get_contents(LOGS . 'debug.log'),
            'worker' => @file_get_contents(LOGS . 'worker.log'),
            'relic'  => @file_get_contents(LOGS . 'relic.log')
        ));
    }

    /**
     * Add, edit, and delete users.
     */
    public function users() {
        $this->User->recursive = -1;
        $this->User->flatten = true;
        $this->set('users', $this->User->find('all', array(
            'order' => 'User.created'
        )));
    }

    /**
     * View and re-run jobs.
     */
    public function jobs() {
        $this->set('jobs', $this->Job->find('all', array(
            'order' => 'Job.created DESC'
        )));
    }
}
