<?php

class MetadataController extends AppController {
    $name = 'Metadata';

    public function beforeFilter() {
        parent::beforeFilter();
        if (!$this->request->is('ajax')) {
            return $this->redirect('/400');
        }
    }

    public function add() {
        if (!$this->request->is('post')) return $this->jsonResponse(400);

        if ($this->Metadatum->add($this->request->data)) 
            return $this->jsonResponse(201);
        else 
            return $this->jsonResponse(400);
    }

    public function edit($id) {
        if (!($this->request->is('post') || $this->request->is('put')))
            return $this->jsonResponse(400);
        if ($this->Metadatum->add($this->request->data))
            return $this->jsonResponse(200);
        else
            return $this->jsonResponse(400);
    }
}
