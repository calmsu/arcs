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
    }

    /**
     * Display a user's bookmarks.
     */
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

    /**
     * Add a new user.
     */
    public function add() {
        if ($this->Auth->loggedIn()) {
            $this->Session->setFlash("You can't signup while logged in.", 
                                     'flash_error');
            $this->redirect('/');
        }
        if ($this->request->is('post') && $this->data) {
            # Save the new user.
            if ($this->User->save($this->data)) {
                # Log them in after signup.
                $id = $this->User->id;
                $this->request->data['User'] = array_merge(
                    $this->request->data["User"], 
                    array('id' => $id)
                );
                $this->Auth->login($this->request->data['User']);
                # Redirect home.
                $this->redirect('/');
            }
        }
    }

    /**
     * Display the login form or authenticate a POSTed form.
     */
    public function login() {
        $this->set('toolbar', false);
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

    /**
     * Logout the user.
     */
    public function logout() {
        $this->redirect($this->Auth->logout());
    }

    /**
     * Display information about a user.
     *
     * @param ref  username or id of an existing user
     */
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
        if ($this->requestIs('ajax', 'get'))
            return $this->json(200, $this->User->find('list', array(
                'fields' => array('User.name')
            )));
        $this->redirect('/404');
    }
}
