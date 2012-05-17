<?php
/**
 * Zip Task
 *
 * Construct a zipfile given an array of resource ids.
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class ZipTask extends AppShell {
    public $uses = array('Resource');

    public function execute($data) {
        $resources = $this->Resource->find('all', array(
            'conditions' => array(
                'Resource.id' => $data['resources']
            )
        ));
        $files = array();
        foreach ($resources as $r) {
            $files[$r['Resource']['file_name']] = $r['Resource']['sha'];
        }
        $this->out($this->Resource->makeZipfile($files));
    }
}
