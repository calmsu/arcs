<?php

/**
 * namespace
 */
namespace Arcs;

require_once(LIB . 'SolrPhpClient' . DS . 'Apache' . DS . 'Solr' .  DS . 'Service.php');
require_once(LIB . 'Arcs' . DS . 'Utilities.php');

/**
 * SolrService class
 *
 * Base class for establishing a connection to our SOLR service, as a wrapper to
 * the SolrPhpClient library.
 */
class SolrService {
    public function __construct($host='localhost', $port=8983, $webapp='/solr/') {
        $this->solr = new \Apache_Solr_Service($host, $port, $webapp);
        if (!$this->solr->ping()) {
            echo "Could not connect to SOLR.\n";
            exit(1);
        }
    }
}

/**
 * SolrSearch class
 *
 * Query the SOLR service for resources, providing an API similar to 
 * \Arcs\SqlSearch.
 */
class SolrSearch extends SolrService {

    protected $_facets = array();
    public $publicFilter = true;

    public function search($query=null, $limit=30, $offset=0) {
        if (is_array($query)) {
            $this->_addFacets($query);
            $query = $this->_facetsToString($query);
        }
        if ($this->publicFilter)
            $this->_addFacet('access', 'public');
        $results = $this->solr->search($query, $offset, $limit);
        # Repackage the results to play nicely with SqlSearch.
        return array(
            'total' => $results->response->numFound,
            'limit' => $limit,
            'offset' => $offset,
            'mode' => 'solr',
            'query '=> $query,
            'results' => $this->_extractIds($results),
            'num_results' => $limit
        );
    }

    protected function _addFacet($cat, $val) {
        $this->_facets[] = array(
            'category' => $cat,
            'value' => $val
        );
    }

    protected function _addFacets($facets) {
        foreach ($facets as $f) {
            $this->_addFacet($f['category'], $f['value']);
        }
    }

    protected function _facetsToString() {
        $facets = array_map(function($f) {
            return $f['category'] . ':' . $f['value'];
        }, $this->_facets);
        return implode(' ', $facets);
    }

    protected function _extractIds($results) {
        return array_map(function($doc) { return $doc->id; },
            $results->response->docs);
    }
}

/**
 * SolrIndexer class
 *
 * Add one or more ARCS resources to the SOLR index.
 */
class SolrIndexer extends SolrService {
    public function addResources($resources) {
        foreach($resources as $r) $this->addResource($r);
    }

    public function addResource($resource) {
        if (!is_array($resource)) return false;

        $fields = array(
            'id'         => $resource['Resource']['id'],
            'sha'        => $resource['Resource']['sha'],
            'user'       => $resource['User']['name'],
            'filetype'   => $resource['Resource']['mime_type'],
            'filename'   => $resource['Resource']['file_name'],
            'type'       => $resource['Resource']['type'],
            'title'      => $resource['Resource']['title'],
            'public'     => $resource['Resource']['public'],
            'modified'   => $this->_formatDate($resource['Resource']['modified']),
            'created'    => $this->_formatDate($resource['Resource']['created']),
            'comment'    => \_\pluck($resource['Comment'], 'content'),
            'annotation' => \_\pluck($resource['Annotation'], 'caption'),
            'keyword'    => \_\pluck($resource['Keyword'], 'keyword'),
            'collection' => $resource['Collection'] ?: array()
        );
        $document = new \Apache_Solr_Document();
        foreach ($fields as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $subval) {
                    $document->addField($key, $subval);
                } 
            } else {
                $document->$key = $val;
            }
        }
        foreach ($resource['Metadatum'] as $m) {
            $document->addField($m['attribute'] . '_t', $m['value']);
        }
        $this->solr->addDocument($document);
        $this->solr->commit();
        $this->solr->optimize();
    }

    public function deleteResource($id) {
        $this->solr->deleteById($id);
        $this->solr->commit();
        $this->solr->optimize();
    }

    protected function _formatDate($date) {
        date_default_timezone_set('UTC');
        return date("Y-m-d\TH:i:s\Z", strtotime($date));
    }
}
