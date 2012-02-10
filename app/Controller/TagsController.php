<?php
App::uses('MetaResourcesController', 'Controller');
/**
 * Tags controller.
 *
 * This controller will only respond to ajax requests.
 *
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class TagsController extends MetaResourcesController {
    public $name = 'Tags';

    /**
     * Complete tag names.
     */
    public function complete() {
        $this->jsonResponse(200, $this->Tag->find('list', array(
            'fields' => array('Tag.tag')
        )));
    }

}
