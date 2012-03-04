<?php
/**
 * Uploads controller.
 *
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class UploadsController extends AppController {
    public $name = 'Uploads';

    public function beforeFilter() {
        parent::beforeFilter();
        $this->RequestHandler->addInputType('json', array('json_decode', true));
    }

    public function add() {
        $files = array();
        foreach($_FILES as $f) {
            $files[] = array(
                'Upload' => array(
                    'file_name' => $f['name'],
                    'tmp_name' => $f['tmp_name']
                )
            );
        }
        $this->jsonResponse(200, $files);
    }

    public function batch() {
    }
}
