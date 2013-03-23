<?php
/**
 * Search controller
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class SearchController extends AppController {
    public $name = 'Search';
    public $uses = array('Resource');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('search', 'resources');
        if (!isset($this->request->query['related'])) {
            $this->Resource->recursive = -1;
            $this->Resource->flatten = true;
        }
    }

    /**
     * Display the search page
     */
    public function search($query='') {
        $title = 'Search';
        if ($query) $title .= ' - ' . urldecode($query);
        $this->set('title_for_layout', $title);
    }

    /**
     * Search resources.
     */
    public function resources() {
        $options = $this->parseParams();

        if (!isset($this->request->query['q']))
            return $this->emptySearch($options);

        $searcher = $this->getSearcher();
        if ($this->Auth->loggedIn())
            $searcher->publicFilter = false;
        $query = $searcher->parseQuery($this->request->query['q']);

        # Get the result ids.
        $response = $searcher->search($query, $options);

        if ($response['order'] == 'relevance') {
            $response['results'] = $this->Resource->findAllFromIds($response['results']);
        } else {
            $response['results'] = $this->Resource->find('all', array(
                'conditions' => array('Resource.id' => $response['results']),
                'order' => "Resource.{$options['order']} {$options['direction']}"
            ));
        }
        # The searcher will return debug information that should be hidden for
        # most account types.
        if (!$this->Access->isAdmin()) {
            unset($response['raw_query']);
            unset($response['mode']);
        }
        $this->json(200, $response);
    }

    /**
     * Complete a category given a query.
     */
    public function complete() {
        $searcher = $this->getSearcher();
        if ($this->Auth->loggedIn()) 
            $searcher->publicFilter = false;

        $category = $this->request->query['cat'];
        $query = $searcher->parseQuery($this->request->query['q']);
        $options = array('limit' => 100);
        $results = $searcher->complete($category, $query, $options);

        $this->json(200, $results);
    }

    /**
     * Return recently modified resources if no search query was givem.
     */
    public function emptySearch($options) {
        # No facets provided. Give them back some recent resources.
        $resources = $this->Resource->find('all', array(
            'conditions' => $this->Auth->loggedIn() ? 
                null: array('Resource.public' => 1),
            'limit' => $options['limit'],
            'offset' => $options['offset'],
            'order' => "Resource.{$options['order']} {$options['direction']}"
        ));
        $this->json(200, array(
            'results' => $resources,
            'num_results' => count($resources),
            'limit' => $options['limit'],
            'mode' => 'no_search',
            'offset' => $options['offset'],
            'direction' => $options['direction'],
            'total' => $this->Resource->find('count', array(
                'conditions' => $this->Auth->loggedIn() ? 
                    null: array('Resource.public' => 1)
                )
            )
        ));
    }

    /**
     * Parse the search options from the request parameters.
     *
     * @return array
     */
    protected function parseParams() {
        $params = $this->request->query;
        $options = array(
            'limit' => isset($params['n']) ? $params['n'] : 30,
            'page' => 1,
            'direction' => 'DESC',
            'order' => 'modified'
        );
        if (isset($params['order'])) {
            $orderables = array('modified', 'created', 'title');
            if (in_array($params['order'], $orderables)) 
                $options['order'] = $params['order'];
        }
        if (isset($params['page'])) $options['page'] = $params['page'];
        $options['offset'] = ($options['page'] - 1) * $options['limit'];
        if (isset($params['direction']) && $params['direction'] == 'asc') {
            $options['direction'] = 'ASC';
        }
        return $options;
    }

    /**
     * Return an instance of a search class. SolrSearch if available, otherwise
     * SqlSearch. This depends on the `arcs.ini` configuration file.
     *
     * @return object
     */
    protected function getSearcher() {
        if (Configure::read('solr.uses')) {
            require_once(LIB . 'Arcs' . DS . 'Solr.php');
            return new \Arcs\SolrSearch(
                Configure::read('solr.host'),
                Configure::read('solr.port'),
                Configure::read('solr.webapp')
            );
        }
        require_once(LIB . 'Arcs' . DS . 'SqlSearch.php');
        $dbo = $this->Resource->getDataSource('default');
        $config = $dbo->config;
        return new \Arcs\SqlSearch($config);
    }
}
