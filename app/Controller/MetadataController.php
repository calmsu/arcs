<?php
/**
 * Metadata controller.
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class MetadataController extends AppController {
    $name = 'Metadata';

    public function beforeFilter() {
        parent::beforeFilter();
        if (!$this->request->is('ajax')) {
            return $this->redirect('/400');
        }
    }

    public function add() {
        if (!$this->request->is('post')) return $this->json(400);

        if ($this->Metadatum->add($this->request->data)) 
            return $this->json(201);
        else 
            return $this->json(400);
    }

    public function edit($id) {
        if (!($this->request->is('post') || $this->request->is('put')))
            return $this->json(400);
        if ($this->Metadatum->add($this->request->data))
            return $this->json(200);
        else
            return $this->json(400);
    }
}
