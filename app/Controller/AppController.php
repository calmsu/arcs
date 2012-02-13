<?php
/**
 * Application Controller
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
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
            'username' => $this->Auth->user('username')
        ));
        $this->set('toolbar', array(
            'logo' => true,
            'buttons' => array()
        ));
        $this->set('dev', Configure::read('debug'));
    }

    /**
     * JSON helper for the API-centric controllers.
     *
     * Sets the response content header to 'application/json', sets the given
     * HTTP status code (or 200, if not given), and delivers the (possibly 
     * empty) payload.
     *
     * @param code       HTTP status code to set, 200 (OK) by default.
     * @param data       Payload to deliver, empty array by default.
     * @param render     Render path to use, '/Elements/ajax' by default.
     */
    public function jsonResponse($code=200, $data=null, 
                                 $render='/Elements/ajax') {
        if (is_null($data)) {
            $data = array();
        }
        $this->response->statusCode($code);
        $this->RequestHandler->respondAs('json');
        $this->set('response', $data);
        $this->render($render);
    }
}
