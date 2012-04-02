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
        $this->Auth->allow('signup', 'login', 'reset_password');
        $this->User->flatten = true;
        $this->User->recursive = -1;
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

    public function add() {
        if (!($this->request->is('post') && $this->request->data))
            return $this->json(400);
        if ($this->Auth->user('role') == 0)
            $this->User->permit('role');
        if (!$this->User->add($this->request->data)) return $this->json(400);
        $this->json(201, $this->User->findById($this->User->id));
    }

    /**
     * User signup.
     */
    public function signup() {
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
     * Edit a user.
     *
     * @param string $id   user id
     */
    public function edit($id=null) {
        if (!($this->request->is('put') || $this->request->is('post'))) 
            return $this->json(405);
        if (!$this->request->data || !$id) return $this->json(400);
        $user = $this->User->read(null, $id);
        if (!$user) return $this->json(404);
        # Must be editing own account, or an admin.
        if (!($this->User->id == $this->Auth->user('id') || $this->Auth->user('role') == 0))
            return $this->json(403);
        # Only admins can change user roles.
        if ($this->Auth->user('role') == 0) {
            $this->User->permit('role');
            $this->User->forbid('password');
        }
        if (!$this->User->add($this->request->data)) return $this->json(500);
        # Update the Auth Session var, if necessary.
        if ($id == $this->Auth->user('id'))
            $this->Session->write('Auth.User', $this->User->findById($id));
        $this->json(200, $this->User->findById($id));
    }

    public function delete($id=null) {
        if (!$this->request->is('delete')) return $this->json(405);
        if (!$this->Auth->user('role') == 0) return $this->json(403);
        if (!$this->User->findById($id)) return $this->json(404);
        if (!$this->User->delete($id)) return $this->json(500);
        $this->json(204);
    }

    /**
     * Display the login form or authenticate a POSTed form.
     *
     * @param string $id   user id
     */
    public function login() {
        $this->set('toolbar', false);
        $this->set('footer', false);
        $this->User->flatten = false;
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
     * Reset the user's password.
     */
    public function reset_password() {
    }

    /**
     * Display the user's profile.
     *
     * @param string $ref  username or id of an existing user
     */
    public function profile($ref) {
        $this->User->flatten = false;
        $this->User->recursive = 1;
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
        if (!$this->request->is('get')) return $this->json(405);
        return $this->json(200, $this->User->complete('User.name'));
    }
}
