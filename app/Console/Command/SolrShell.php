<?php
require_once(LIB . 'Arcs' . DS . 'Solr.php');
/**
 * Solr shell
 *
 * Provides methods for bulk-indexing the SOLR service.
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class SolrShell extends AppShell {

    public $uses = array('Resource', 'Collection');

    public function index_all() {
        $indexer = new \Arcs\SolrIndexer();
        $offset = 0;
        while (true) {
            $resources = $this->Resource->find('all', array(
                'limit' => 50,
                'offset' => $offset
            ));
            if (!$resources) break;
            foreach($resources as $r) {
                $id = $r['Resource']['id'];
                $cids = $this->Resource->Membership->memberships($id); 
                $r['Collection'] = $this->Collection->find('list', array(
                    'fields' => 'Collection.title',
                    'conditions' => array(
                        'Collection.id' => $cids
                    )
                ));
                $this->out("INDEX $id");
                $indexer->addResource($r);
            }
            $offset += 50;
        }
    }

    public function delete_all() {
        $indexer = new \Arcs\SolrIndexer();
        $offset = 0;
        while (true) {
            $resources = $this->Resource->find('all', array(
                'limit' => 50,
                'offset' => $offset
            ));
            if (!$resources) break;
            foreach($resources as $r) {
                $id = $r['Resource']['id'];
                $this->out("DELETE $id");
                $indexer->deleteResource($id);
            }
            $offset += 50;
        }
    }
}
