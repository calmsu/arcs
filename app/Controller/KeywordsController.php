<?php
App::uses('MetaResourcesController', 'Controller');
/**
 * Keywords controller.
 *
 * This controller will only respond to ajax requests.
 *
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class KeywordsController extends MetaResourcesController {
    public $name = 'Keywords';

    /**
     * Complete keywords.
     */
    public function complete() {
        $this->json(200, $this->Keyword->complete('Keyword.keyword'));
    }
}
