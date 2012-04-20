<?php
require_once(LIB . 'Arcs' . DS . 'Solr.php');

/**
 * Delete a resource from the SOLR index. 
 */
class SolrIndexTask extends AppShell {

    public function execute($data) {
        $this->_loadModels();
        $indexer = new \Arcs\SolrIndexer();
        $indexer->deleteResource($data['resource_id']);
    }
}
