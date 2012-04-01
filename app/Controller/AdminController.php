<?php
App::uses('ConnectionManager', 'Model');
/**
 * Admin controller.
 *
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class AdminController extends AppController {
    public $name = 'Admin';

    public function beforeFilter() {
        parent::beforeFilter();
        if (!$this->Auth->user('role') == 0) {
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
        $uploads_path = Configure::read('paths.uploads');
        $this->set('uploads', array(
            'url' => Configure::read('urls.uploads'),
            'path' => $uploads_path,
            'exists' => is_dir($uploads_path),
            'writable' => is_writable($uploads_path),
            'executable' => is_executable($uploads_path)
        ));
        $this->set('dependencies', array(
            'Ghostscript' => is_executable(Configure::read('executables.ghostscript')),
            'Imagemagick' => class_exists('Imagick')
        ));
    }

    /**
     * Displays the error and debug logs.
     */
    public function logs() {
        $this->set(array(
            'error' => @file_get_contents(LOGS . 'error.log'),
            'debug' => @file_get_contents(LOGS . 'debug.log'),
            'relic' => @file_get_contents(LOGS . 'relic.log')
        ));
    }

    /**
     * Add, edit, and delete users.
     */
    public function users() {
        $this->loadModel('User');
        $this->User->recursive = -1;
        $this->User->flatten = true;
        $this->set('users', $this->User->find('all', array(
            'order' => 'User.created'
        )));
    }

    /**
     * View and re-run tasks.
     */
    public function tasks() {
        $this->loadModel('Task');
        $this->Task->recursive = -1;
        $this->Task->flatten = true;
        $this->set('tasks', $this->Task->find('all', array(
            'order' => 'Task.created DESC'
        )));
    }
}
