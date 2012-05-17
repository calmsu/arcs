<?php
require_once(LIB . 'Arcs' . DS . 'Solr.php');
/**
 * SolrDelete Task
 *
 * Delete a resource from the SOLR service.
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class SolrDeleteTask extends AppShell {

    public function execute($data) {
        $this->_loadModels();
        $indexer = new \Arcs\SolrIndexer();
        $indexer->deleteResource($data['resource_id']);
    }
}
