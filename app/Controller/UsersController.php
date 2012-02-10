<?php
/**
 * Users Controller
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class UsersController extends AppController {
    public $name = 'Users';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'login');
        
        $this->set('toolbar', array(
            'logo' => true,
            'buttons' => array()
        ));
    }

    public function index() {
        $this->set('users', $this->User->find('all'));
        $this->set('_serialize', array('users'));
    }

    public function bookmarks($ref) {
        $user = $this->User->findByRef($ref);
        if (!$user) {
            $this->redirect('404');
        }
        $id = $user['User']['id'];
        $this->set('bookmarks', $this->User->Bookmark->find('all', array(
            'conditions' => array(
                'Bookmark.user_id' => $id
        ))));
    }

    public function add() {
        if ($this->Auth->loggedIn()) {
            $this->Session->setFlash("You can't signup while logged in.", 
                                     'flash_error');
            $this->redirect('/');
        }
        if ($this->request->is('post') && $this->data) {
            if ($this->User->save($this->data)) {
                $this->redirect('/');
            }
        }
    }

    public function login() {
        $redirect = $this->Session->read('redirect');
        if ($redirect) {
            $this->Session->write('Auth.redirect', $redirect);
            $this->Session->delete('redirect');
        }
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash('Incorrect username/password combination',
                                         'flash_error');
            }
        }
    }

    public function logout() {
        $this->redirect($this->Auth->logout());
    }

    public function view($ref) {
        $user = $this->User->findByRef($ref);
        if (!$user) {
            $this->redirect('404');
        }
        $this->set('user_info', $user);
    }

    /**
     * Return an array containing values from the users.name column, for
     * autocompletion purposes. Responds only to ajax requests.
     */
    public function complete() {
        if ($this->request->is('ajax')) {
            $this->jsonResponse(200, $this->User->find('list', array(
                'fields' => array('User.name')
            )));
        } else {
            $this->redirect('/404');
        }
    }
}
