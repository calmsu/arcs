<?php
require_once(LIB . 'Arcs' . DS . 'Solr.php');

class SolrIndexTask extends AppShell {
    public $uses = array('Resource', 'Collection');

    public function execute($data) {
        $this->_loadModels();
        $resource = $this->Resource->findById($data['resource_id']);
        if (!$resource) return false;
        # Populate collections
        $cids = $this->Resource->Membership->memberships($data['resource_id']); 
        $resource['Collection'] = $this->Collection->find('list', array(
            'fields' => 'Collection.title',
            'conditions' => array(
                'Collection.id' => $cids
            )
        ));
        $indexer = new \Arcs\SolrIndexer();
        $indexer->addResource($resource);
    }
}
