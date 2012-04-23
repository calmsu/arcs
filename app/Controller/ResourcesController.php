<?php 
/**
 * Resources Controller
 *
 * Logic for retrieving and presenting resources.
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class ResourcesController extends AppController {
    public $name = 'Resources';
    public $uses = array('Resource', 'Job', 'Collection');

    public function beforeFilter() {
        # The App Controller will set some common view variables (namely a 
        # user array), so the parent's beforeFilter is run in this and most
        # other controllers.
        parent::beforeFilter();

        # Read-only actions, such as viewing resources and associated comments
        # are allowed by default.
        $this->Auth->allow(
            'index', 'view', 'viewer', 'search', 'comments', 'hotspots', 
            'keywords', 'complete', 'zipped', 'download'
        );

        if (!isset($this->request->query['related'])) {
            $this->Resource->recursive = -1;
            $this->Resource->flatten = true;
        }
    }

    /**
     * Create a resource.
     *
     * This is not currently implemented. The Uploads controller handles file
     * uploading (and subsequent resource creation). This action will likely
     * handle API requests.
     */
    public function add() {
        # TODO
        if (!$this->request->is('post')) throw new MethodNotAllowedException();
        $this->json(501);
    }

    /**
     * Creates a task to split a PDF into individual resources. Note it doesn't
     * actually do any splitting within the Request-Response loop.
     * 
     * @param string $id    resource id
     */
    public function split_pdf($id=null) {
        if (!$this->request->is('post')) throw new MethodNotAllowedException();
        if (!$id) throw new BadRequestException(); 
        $resource = $this->Resource->findById($id);
        if (!$resource) throw new NotFoundException();
        if (!$resource['mime_type'] == 'application/pdf') 
            throw new BadRequestException();

        # Create a new collection for the split.
        $this->Collection->permit('user_id');
        $this->Collection->add(array(
            'title' => $resource['title'],
            'description' => 'PDF split of ' . $resource['title'],
            'public' => $resource['public'],
            'user_id' => $this->Auth->user('id'),
            'pdf' => $id
        ));

        # Make a new task to split the PDF.
        $this->Job->enqueue('split_pdf', array(
            'resource_id' => $id, 
            'collection_id' => $this->Collection->id,
            'type' => 'Notebook Page'
        ));
        $this->json(202);
    }

    /**
     * Edit the resource.
     *
     * @param string $id    resource id
     */
    public function edit($id=null) {
        if (!($this->request->is('post') || $this->request->is('put'))) 
            throw new MethodNotAllowedException();
        $resource = $this->Resource->findById($id);
        if (!$resource) throw new NotFoundException();
        if ($this->Resource->add($this->request->data)) return $this->json(200);
        throw new InternalErrorException();
    }

    /**
     * The Resource viewer.
     *
     * @param string $id            resource id
     * @param bool   $ignore_ctx    if true, the action will not redirect to the
     *                              collection view when the resource has a 
     *                              non-null context attribute. 
     */
    public function viewer($id, $ignore_ctx=false) {
        $this->Resource->recursive = 1;
        $this->Resource->flatten = false;

        $resource = $this->Resource->findById($id);
        $public = $resource['Resource']['public'];
        $allowed = $public || $this->Auth->loggedIn();

        if (!$resource) return $this->redirect('/404');
        if (!$allowed) {
            $this->Session->setFlash("Oops. You'll need to login to view that.", 'flash_error');
            $this->Session->write('redirect', '/resource/' . $id);
            return $this->redirect($this->Auth->redirect('/users/login'));
        }
        
        # Redirect if the resource's context is non-null.
        if ($resource['Resource']['context'] && !$ignore_ctx) {
            return $this->redirect('/collection/' . 
                $resource['Resource']['context'] . '/' . $id
            );
        }

        $this->set('memberships', $this->Resource->Membership->find('all', array(
            'conditions' => array('Membership.resource_id' => $id)
        )));
        $this->set('resource', $resource);
        $this->set('toolbar', array('actions' => true));
        $this->set('footer', false);
        $this->set('body_class', 'viewer standalone');

        # On the first request of a particular resource (usually directly 
        # after upload), we might prompt the user for additional 
        # actions/information. Here we're turning that off for future 
        # requests. (Note that the first_req will still be true within the 
        # $resource var.)
        if ($resource['Resource']['first_req']) 
            $this->Resource->firstRequest($resource['Resource']['id']);
    }

    /**
     * Return resource info.
     *
     * @param string $id    resource id
     */
    public function view($id=null) {
        if (!$this->request->is('get')) throw new MethodNotAllowedException();
        if (!$id) throw new BadRequestException();
        $resource = $this->Resource->findById($id);
        if (!$resource) throw new NotFoundException();
        $public = $resource['Resource']['public'];
        $allowed = $public || $this->Auth->loggedIn();
        if (!$allowed) throw new ForbiddenException();
        $this->json(200, $resource);
    }

    /**
     * Delete the resource, if authorized.
     *
     * @param string $id    resource id
     */
    public function delete($id=null) {
        if (!$this->request->is('delete')) throw new MethodNotAllowedException();
        if (!$this->Auth->loggedIn()) throw new UnauthorizedException();
        if (!$this->Resource->delete($id)) throw new InternalErrorException();
        $this->json(204);
    }

    /**
     * Search resources.
     */
    public function search() {
        $public = !$this->Auth->loggedIn();
        # Get the request parameters.
        $params = $this->request->query;
        $limit = isset($params['n']) ? $params['n'] : 30;
        $offset = isset($params['offset']) ? $params['offset'] : 0;

        $order = 'modified';
        if (isset($params['order'])) {
            $orderables = array('modified', 'created', 'title');
            if (in_array($params['order'], $orderables)) 
                $order = $params['order'];
        }

        if ($this->request->data) {
            # Instantiate our Search object with the db config and facets.
            $searcher = $this->_getSearcher();

            # Get the result ids.
            $response = $searcher->search($this->request->data, $limit, $offset);
            $response['results'] = $this->Resource->find('all', array(
                'conditions' => array(
                    'Resource.id' => $response['results']
                ),
                'order' => "Resource.$order DESC"
            ));
            if (!$this->Access->isAdmin()) {
                unset($response['raw_query']);
                unset($response['mode']);
            }
            return $this->json(200, $response);
        }

        # No facets provided. Give them back some recent resources.
        $resources = $this->Resource->find('all', array(
            'conditions' => $public ? array('Resource.public' => 1) : null,
            'limit' => $limit,
            'offset' => $offset,
            'order' => "Resource.$order DESC"
        ));
        $this->json(200, array(
            'results' => $resources,
            'num_results' => count($resources),
            'limit' => $limit,
            'offset' => $offset,
            'total' => $this->Resource->find('count')
        ));
    }

    /**
     * Return an instance of a search class. SolrSearch if available, otherwise
     * SqlSearch. This depends on the `arcs.ini` configuration file.
     *
     * @return object
     */
    private function _getSearcher() {
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

    /**
     * Render a resource file using the download element. The download element
     * will set the file headers, which include an ambiguous Content-type to
     * 'force' the download.
     *
     * @param string $id   resource id
     */
    public function download($id) {
        $resource = $this->Resource->findById($id);
        if (!$resource) throw new NotFoundException();
        $this->layout = false;
        Configure::write('debug', 0);
        $sha = $resource['sha'];
        $this->set('fname', $resource['file_name']);
        $this->set('fsize', $resource['file_size']);
        $this->set('path', $this->Resource->path($sha, $sha));
        $this->render('/Elements/download');
    }

    /**
     * Create a zipfile of the POSTed array of resources. Responds with a JSON
     * object containing a url to the zipfile.
     */
    public function zipped() {
        # TODO: Look into streaming the zipfile, vs. making it and then providing
        # the link...
        if (!($this->request->is('post') && $this->request->data))
            throw new BadRequestException();
        $ids = $this->request->data['resources'];
        $resources = $this->Resource->find('all', array(
            'conditions' => array(
                'Resource.id' => $ids
            )
        ));
        $files = array();
        foreach ($resources as $r) {
            $files[$r['file_name']] = $r['sha'];
        }
        $title = str_replace(' ', '-', $resources[0]['title']);
        $name = $title . '-and-' . 
            (count($files) - 1) . '-' .
            (count($files) > 2 ? 'others' : 'other');
        $sha = $this->Resource->makeZipfile($files, $name);
        $this->json(200, array(
            'url' => $this->Resource->url($sha, $name . '.zip')
        ));
    }

    /**
     * Request a re-thumbnail of a resource's thumbnail image. This is handled
     * through the Job Worker. We'll respond with a 202 status code if
     * everything checks out.
     *
     * @param string $id   resource id
     */
    public function rethumb($id) {
        if (!$this->request->is('post')) throw new BadRequestException();
        if (!$this->Auth->loggedIn()) throw new UnauthorizedException();
        $resource = $this->Resource->findById($id);
        if (!$resource) throw new NotFoundException();
        $this->Job->enqueue('thumb', array(
            'resource_id' => $resource['id']
        ));
        $this->json(202);
    }

    /**
     * Request that a resource's preview image be redone.
     *
     * @param string $id   resource id
     */
    public function repreview($id) {
        if (!$this->request->is('post')) throw new BadRequestException();
        if (!$this->Auth->loggedIn()) throw new UnauthorizedException();
        $resource = $this->Resource->findById($id);
        if (!$resource) throw new NotFoundException();
        $this->Job->enqueue('preview', array(
            'resource_id' => $resource['id']
        ));
        $this->json(202);
    }

    /**
     * Request that a resource by (re)indexed in SOLR.
     *
     * @param string $id   resource id
     */
    public function solr($id) {
        if (!$this->request->is('post')) throw new BadRequestException();
        if (!$this->Auth->loggedIn()) throw new UnauthorizedException();
        $resource = $this->Resource->findById($id);
        if (!$resource) throw new NotFoundException();
        $this->Job->enqueue('solr_index', array(
            'resource_id' => $resource['id']
        ));
        $this->json(202);
    }

    /**
     * Get or set (depending on HTTP method) metadata for the given resource.
     *
     * @param string $id   resource id
     */
    public function metadata($id) {
        if (!$this->Resource->findById($id)) throw new NotFoundException();
        if (($this->request->is('post') || $this->request->is('put')) && 
            $this->request->data) 
        {
            foreach ($this->request->data as $k => $v)
                $this->Resource->Metadatum->store($id, $k, $v);
            return $this->json(201);
        }
        if ($this->request->is('get'))
            return $this->json(200, $this->Resource->Metadatum->find('all', array(
                'conditions' => array(
                    'Metadatum.resource_id' => $id
            ))));
        throw new BadRequestException();
    }

    /**
     * Return associated comments.
     *
     * @param string $id    resource id
     */
    public function comments($id=null) {
        if (!$this->request->is('get') || !$id) throw new BadRequestException();
        $this->json(200, $this->Resource->Comment->find('all',
            array('conditions' => array('Resource.id' => $id))
        ));
    }

    /**
     * Return associated keywords.
     *
     * @param string $id    resource id
     */
    public function keywords($id=null) {
        if (!$this->request->is('get') || !$id) throw new BadRequestException();
        $this->json(200, $this->Resource->Keyword->find('all',
            array('conditions' => array('Resource.id' => $id))
        ));
    }

    /**
     * Return associated hotspots.
     *
     * @param string $id    resource id
     */
    public function hotspots($id=null) {
        if (!$this->request->is('get') || !$id) throw new BadRequestException();
        $this->json(200, $this->Resource->Hotspot->find('all',
            array('conditions' => array('Resource.id' => $id))
        ));
    }

    /**
     * Return a list of values for autocompletion.
     *
     * @param string $field   Resource field to complete.
     */
    public function complete($field=null) {
        if (!$this->request->is('get') || !$id) throw new BadRequestException();
        switch ($field) {
            case 'title':
                $values = $this->Resource->complete('Resource.title');
                break;
            case 'created':
                $values = $this->Resource->complete('Resource.created');
                break;
            case 'modified':
                $values = $this->Resource->complete('Resource.modified');
                break;
            case 'type':
                $values = Configure::read('resources.types');
                break;
            default:
                $values = array();
        }
        $this->json(200, $values);
    }
}
