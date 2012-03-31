<?php
/**
 * Application Controller
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
App::uses('Xml', 'Utility');
class AppController extends Controller {
    public $helpers = array('Html', 'Form', 'Session', 'Assets');

    public $components = array(
        'Auth' => array(
            'authenticate' => array('Form'),
            'authError' => "Sorry, you'll need to login to do that."
        ),
        'Session',
        'RequestHandler'
    );

    public function beforeFilter() {
        $this->set('user', array(
            'loggedIn' => $this->Auth->loggedIn(),
            'id' => $this->Auth->user('id'),
            'name' => $this->Auth->user('name'),
            'email' => $this->Auth->user('email'),
            'role' => intVal($this->Auth->user('role')),
            'username' => $this->Auth->user('username')
        ));
        $this->set('toolbar', array(
            'logo' => true,
            'buttons' => array()
        ));
        $this->set('footer', true);
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
     * @param  int $code        HTTP status code to set, 200 (OK) by default.
     * @param  mixed $data      Payload to deliver, is coerced to an array.
     * @param  string $render   Render path to use, '/Elements/ajax' by default.
     * @return void
     */
    public function json($code=200, $data=null) {
        $this->layout = 'ajax';
        $this->response->statusCode($code);
        $this->RequestHandler->respondAs('json');
        $response = json_encode((array)$data);
        $this->set('response', $response);
        $this->render('/Layouts/ajax');
    }
}
