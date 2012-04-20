<?php
App::uses('Controller', 'Controller');
/**
 * Application Controller
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class AppController extends Controller {
    public $helpers = array('Html', 'Form', 'Session', 'Assets');
    public $viewClass = 'TwigView.Twig';

    public $components = array(
        'Auth' => array(
            'authenticate' => array('Form'),
            'authError' => "Sorry, you'll need to login to do that."
        ),
        'Session',
        'RequestHandler',
        'Access'
    );

    public function beforeFilter() {
        $this->set(array(
            'user' => array(
                'loggedIn' => $this->Auth->loggedIn(),
                'id' => $this->Auth->user('id'),
                'name' => $this->Auth->user('name'),
                'email' => $this->Auth->user('email'),
                'role' => $this->Auth->loggedIn() ? 
                    intVal($this->Auth->user('role')) : 3,
                'username' => $this->Auth->user('username')
            ),
            'toolbar' => array(
                'logo' => true,
                'buttons' => array()
            ),
            'body_class' => 'default',
            'footer' => true,
            'debug' => Configure::read('debug')
        ));
        $this->RequestHandler->addInputType('json', array('json_decode', true));
    }

    /**
     * Convenience method for multiple Request->is($type) checks.
     *
     * @param  string $args   One or more request types to check.
     * @return bool           True if *all* checks pass.
     */
    public function requestIs($args) {
        $checks = func_get_args();
        foreach($checks as $check)
            if (!$this->request->is($check)) return false;
        return true;
    }

    /**
     * Convenience method for wrapping up JSON response logic.
     *
     * Sets the response content header to 'application/json', sets the given
     * HTTP status code (or 200, if not given), and delivers the (possibly 
     * empty) payload.
     *
     * @param  int $status      HTTP status code to set, 200 (OK) by default.
     * @param  mixed $data      Payload to deliver, is coerced to an array.
     * @return void
     */
    public function json($status=200, $data=null) {
        $this->autoRender = false;
        $this->response->statusCode($status);
        $this->RequestHandler->respondAs('json');
        $this->response->body(json_encode((array)$data, JSON_NUMERIC_CHECK));
    }
}
