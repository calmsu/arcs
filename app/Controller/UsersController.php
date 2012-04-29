<?php
/**
 * Users Controller
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class UsersController extends AppController {
    public $name = 'Users';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('signup', 'login', 'register', 'reset_password');
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

    /**
     * Add a new user.
     */
    public function add() {
        if (!($this->request->is('post') && $this->request->data))
            return $this->json(400);
        if ($this->Access->isAdmin())
            $this->User->permit('role');
        if (!$this->User->add($this->request->data)) return $this->json(400);
        $this->json(201, $this->User->findById($this->User->id));
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
        if (!($this->User->id == $this->Auth->user('id') || $this->Access->isAdmin()))
            return $this->json(403);
        # Only admins can change user roles.
        if ($this->Access->isAdmin()) 
            $this->User->permit('role');
        if (!$this->User->add($this->request->data)) return $this->json(500);
        # Update the Auth Session var, if necessary.
        if ($id == $this->Auth->user('id'))
            $this->Session->write('Auth.User', $this->User->findById($id));
        $this->json(200, $this->User->findById($id));
    }

    /**
     * Delete a user by id.
     *
     * Only an admin can delete users.
     *
     * @param string $id   user id
     */
    public function delete($id=null) {
        if (!$this->request->is('delete')) return $this->json(405);
        if (!$this->Access->isAdmin()) return $this->json(403);
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
        $this->set(array('toolbar' => false, 'footer' => false));
        $this->User->flatten = false;
        $redirect = $this->Session->read('redirect');
        if ($redirect) {
            $this->Session->write('Auth.redirect', $redirect);
            $this->Session->delete('redirect');
        }
        if ($this->request->is('post')) {
            if ($this->request->data['User']['forgot_password'])
                return $this->send_reset($this->request->data['User']['username']);
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash('Incorrect username/password combination',
                                         'flash_error');
            }
        }
    }

    /**
     * Create a reset password link and queue an email to the user.
     *
     * @param string $email
     */
    public function send_reset($email) {
        $user = $this->User->findByEmail($email);
        if (!$user) { 
            $this->Session->setFlash("Sorry, we couldn't find an account with that " . 
                "email address.", 'flash_error');
        } else {
            $token = $this->User->getToken();
            $this->User->permit('reset');
            $this->User->saveById($user['User']['id'], array(
                'reset' => $token
            ));
            $this->Job->enqueue('email', array(
                'to' => $email,
                'subject' => 'Reset Password',
                'template' => 'reset_password',
                'vars' => array(
                    'name' => array_shift(explode(' ', $user['User']['name'])),
                    'reset' => $this->baseURL() . '/users/reset_password/' . $token
                )
            ));
            $this->Session->setFlash("We've sent an email to $email. It contains a special " .
               "link to reset your password.", 'flash_success');
        }
        $this->redirect('/login');
    }

    /**
     * Change the password.
     */
    public function reset_password($token=null) {
        if (!$token) throw new BadRequestException();
        $this->set(array('toolbar' => false, 'footer' => false));
        $user = $this->User->findByReset($token);
        if (!$user || is_null($token)) {
            $this->Session->setFlash("Invalid token.", 'flash_error');
            return $this->redirect('/login');
        }
        if (isset($this->data['User']['password'])) {
            $this->User->permit('password');
            $this->User->saveById($user['id'], array(
                'password' => $this->data['User']['password'],
                'reset' => null
            ));
            $this->Session->setFlash("Your password has been changed. You may now login.", 
                'flash_success');
            $this->redirect('/login');
        }
    }

    /**
     * Logout the user.
     */
    public function logout() {
        $this->redirect($this->Auth->logout());
    }

    /**
     * Send an invite email and set up a skeleton account.
     */
    public function invite() {
        if (!$this->Access->isAdmin()) throw new ForbiddenException();
        if (!$this->request->is('post')) throw new MethodNotAllowedException();
        $data = $this->request->data;
        if (!($data && $data['email'] && $data['role'])) 
            throw new BadRequestException();
        $token = $this->User->getToken();
        $this->User->permit('activation', 'role');
        $this->User->add(array(
            'email' => $data['email'],
            'role' => $data['role'],
            'activation' => $token
        ));
        $this->Job->enqueue('email', array(
            'to' => $data['email'],
            'subject' => 'Welcome to ARCS',
            'template' => 'welcome',
            'vars' => array(
                'activation' => $this->baseURL() . '/register/' . $token
            )
        ));
        $this->json(202);
    }

    public function register($activation) {
        if (!$activation) throw new BadRequestException();
        $user = $this->User->findByActivation($activation);
        if (!$user) throw new NotFoundException();
        if ($this->request->is('post')) {
            $this->User->read(null, $user['id']);
            $this->User->set(array(
                'password' => $this->request->data['User']['password'],
                'username' => $this->request->data['User']['username'],
                'name' => $this->request->data['User']['name'],
                'activation' => null
            ));
            $this->User->save();
            $user = array_merge($user, $this->request->data['User']);
            $this->Auth->login($user);
            $this->redirect('/');
        } else {
            $this->set(array(
                'email' => $user['email'],
                'gravatar' => $user['gravatar']
            ));
        }
    }

    /**
     * Display the user's profile.
     *
     * @param string $ref  username or id of an existing user
     */
    public function profile($ref) {
        $this->User->flatten = false;
        $this->User->recursive = 1;
        $user = $this->User->find('first', array(
            'conditions' => array(
                'OR' => array('User.username' => $ref, 'User.id' => $ref)
            ),
            'contain' => array(
                'Resource'   => array('limit' => 30),
                'Annotation' => array('limit' => 30),
                'Collection' => array('limit' => 30),
                'Comment'    => array('limit' => 30)
            )
        ));
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
