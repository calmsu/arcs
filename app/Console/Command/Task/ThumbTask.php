<?php
/**
 * Thumb Task
 *
 * Thumbnail a resource.
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class ThumbTask extends AppShell {
    public $uses = array('Resource');

    public function execute($data) {
        $this->_loadModels();
        $resource = $this->Resource->findById($data['resource_id']);
        return $this->Resource->makeThumbnail($resource['Resource']['sha']);
    }
}
