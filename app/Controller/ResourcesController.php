<?php 
# Require our Search class.
require_once(LIB . 'arcs' . DS . 'Search.php');

/**
 * Resources Controller
 *
 * Logic for retrieving and presenting resources, largely via ajax.
 * 
 * @package      ARCS
 * @link         http://github.com/calmsu/arcs
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 * @license      
 */
class ResourcesController extends AppController {
    public $name = 'Resources';
    public $uses = array('Resource', 'Task', 'Collection');

    public function beforeFilter() {
        # The App Controller will set some common view variables (namely a 
        # user array), so the parent's beforeFilter is run in this and most
        # other controllers.
        parent::beforeFilter();

        # Read-only actions, such as viewing resources and associated comments
        # are allowed by default.
        $this->Auth->allow(
            'index', 'view', 'search', 'comments',
            'hotspots', 'keywords', 'complete'
        );
    }

    /**
     * Create a resource.
     *
     * This is not currently implemented. The Uploads controller handles file
     * uploading (and subsequent resource creation). This action will likely
     * handle ajax requests.
     */
    public function add() {
        # TODO
        if ($this->requestIs('ajax', 'post')) return $this->json(501);
        $this->redirect('/404');
    }

    /**
     * Creates a task to split a PDF into individual resources. Note it doesn't
     * actually do any splitting within the Request-Response loop.
     * 
     * @param string $id    resource id
     */
    public function split_pdf($id) {
        $resource = $this->Resource->findById($id);
        if ($resource['Resource']['mime_type'] == 'application/pdf') {
            # Create a new collection for the split.
            $this->Collection->permit('user_id');
            $this->Collection->save(array(
                'Collection' => array(
                    'title' => $resource['Resource']['title'],
                    'description' => 'PDF split of ' . $resource['Resource']['title'],
                    'public' => $resource['Resource']['public'],
                    'user_id' => $this->Auth->user('id'),
                    'pdf' => $id
            )));

            # Make a new task to split the PDF.
            $this->Task->queue('split_pdf', $id, $this->Collection->id);

            # If request is ajax, return a response here.
            if ($this->request->is('ajax')) {
                # Return 202 (meaning accepted, but not yet done).
                return $this->json(202);
            }
            # Set a flash message otherwise.
            $this->Session->setFlash('Resource has been queued for splitting.',
                'flash_success');
        } else {
            if ($this->request->is('ajax')) return $this->json(400);
            $this->Session->setFlash('Can only split a PDF file.', 'flash_error');
        }
        $this->redirect(array('action' => 'view', $id));
    }

    /**
     * Update the resource.
     *
     * @param string $id    resource id
     */
    public function update($id) {
        if (!$this->request->is('ajax')) return $this->redirect('/404');
        $resource = $this->Resource->findById($id);
        if (!$resource) return $this->json(404);
        if ($this->Resource->add($this->request->data)) return $this->json(200);
        $this->json(500);
    }

    /**
     * Display the resource, as either HTML or JSON.
     *
     * @param string $id            resource id
     * @param bool   $ignore_ctx    if true, the action will not redirect to the
     *                              collection view when the resource has a 
     *                              non-null context attribute. This is 
     *                              irrelevant for ajax requests; we'll never 
     *                              redirect those.
     */
    public function view($id, $ignore_ctx=false) {
        $resource = $this->Resource->findById($id);
        $public = $resource['Resource']['public'];
        $allowed = $public || $this->Auth->loggedIn();

        if ($this->requestIs('ajax', 'get')) {
            if (!$resource) return $this->json(404);
            if (!$allowed) return $this->json(403);
            return $this->json(200, $resource['Resource']);
        }

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

        # On the first request of a particular resource (usually directly 
        # after upload), we might prompt the user for additional 
        # actions/information. Here we're turning that off for future 
        # requests. (Note that the first_req will still be true within the 
        # $resource var.)
        if ($resource['Resource']['first_req']) $this->Resource->firstRequest();
    }

    /**
     * Delete the resource, if authorized. Ajax only.
     *
     * @param string $id    resource id
     */
    public function delete($id=null) {
        if (!$this->request->is('ajax')) return $this->redirect('/404');
        if (!$this->Auth->loggedIn()) return $this->json(401);
        if (!$this->Resource->delete($id)) return $this->json(500);
        $this->json(200);
    }

    /**
     * Search resources. Results are only returned when requested via ajax.
     */
    public function search() {
        if (!$this->request->is('ajax'))
            return $this->set('title_for_layout', 'Search');

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
            # Get our datasource object ready to give to the Search class.
            $dbo = $this->Resource->getDataSource('default');
            $config = $dbo->config;

            # Instantiate our Search object with the db config and facets.
            $search = new \Arcs\Search($config, $this->request->data);

            # If not logged in, only public resources may be viewed.
            if ($public) $search->public = true;

            # Get the result ids.
            $ids = $search->results($limit, $offset);

            # Return the results in HABTM format.
            return $this->json(200, $this->Resource->find('all', array(
                'conditions' => array(
                    'Resource.id' => $ids
                ),
                'order' => "Resource.$order DESC"
            )));
        }
        # No facets provided. Give them back some random ones.
        $this->json(200, $this->Resource->find('all', array(
            'conditions' => $public ? array('Resource.public' => 1) : null,
            'limit' => $limit,
            'offset' => $offset,
            'order' => "Resource.$order DESC"
        )));
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
        if (!$resource) return $this->redirect('/404');
        $this->layout = false;
        Configure::write('debug', 0);
        $sha = $resource['Resource']['sha'];
        $this->set('fname', $resource['Resource']['file_name']);
        $this->set('fsize', $resource['Resource']['file_size']);
        $this->set('path', $this->Resource->path($sha, $sha));
        $this->render('/Elements/download');
    }

    /**
     * Create a zipfile of the POSTed array of resources. Responds with a JSON
     * object containing a url to the zipfile.
     */
    public function zipped() {
        if (!$this->request->is('ajax')) return $this->redirect('/404');
        if (!($this->request->is('post') && $this->request->data))
            return $this->json(400);
        $ids = $this->request->data['resources'];
        $resources = $this->Resource->find('all', array(
            'conditions' => array(
                'Resource.id' => $ids
            )
        ));
        $files = array();
        foreach ($resources as $r) {
            $files[$r['Resource']['file_name']] = $r['Resource']['sha'];
        }
        $title = str_replace(' ', '-', $resources[0]['Resource']['title']);
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
     * through the Task Worker. We'll respond with a 202 status code if
     * everything checks out.
     *
     * @param string $id   resource id
     */
    public function rethumb($id) {
        if (!$this->request->is('ajax')) return $this->redirect('/404');
        if (!$this->request->is('post')) return $this->json(400);
        if (!$this->Auth->user('id')) return $this->json(401);
        $resource = $this->Resource->findById($id);
        if (!$resource) return $this->json(404);
        $this->loadModel('Task');
        $this->Task->queue('thumb', $resource['Resource']['id']);
        $this->json(202);
    }

    /**
     * Creates (or replaces) Metadata for the given resource, given an
     * ajax POST with a JSON object or properties.
     *
     * @param string $id   resource id
     */
    public function edit_info($id) {
        if (!$this->requestIs('ajax', 'post')) return $this->redirect('/404');
        if (!$this->Resource->findById($id)) return $this->json(404);
        foreach ($this->request->data as $k => $v)
            $this->Resource->Metadatum->store($id, $k, $v);
        $this->json(200);
    }

    /**
     * Return associated comments.
     *
     * @param string $id    resource id
     */
    public function comments($id) {
        if (!$this->requestIs('ajax', 'get')) return $this->json(400);
        $this->json(200, $this->Resource->Comment->find('all',
            array('conditions' => array('Resource.id' => $id))
        ));
    }

    /**
     * Return associated keywords.
     *
     * @param string $id    resource id
     */
    public function keywords($id) {
        if (!$this->requestIs('ajax', 'get')) return $this->json(400);
        $this->json(200, $this->Resource->Keyword->find('all',
            array('conditions' => array('Resource.id' => $id))
        ));
    }

    /**
     * Return associated hotspots.
     *
     * @param string $id    resource id
     */
    public function hotspots($id) {
        if (!$this->requestIs('ajax', 'get')) return $this->json(400);
        $this->json(200, $this->Resource->Hotspot->find('all',
            array('conditions' => array('Resource.id' => $id))
        ));
    }

    /**
     * Return a list of values for autocompletion.
     *
     * @param string $field   Resource field to complete.
     */
    public function complete($field) {
        if (!$this->requestIs('ajax', 'get')) return $this->json(400);
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
