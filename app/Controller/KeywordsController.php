<?php
App::uses('MetaResourcesController', 'Controller');
/**
 * Keywords controller.
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
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
