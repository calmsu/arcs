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
     * Upload a new resource.
     *
     * @param collection_id    optional, the collection to add the resource to.
     */
    public function add($collection_id=null) {
        if ($this->request->is('post') && $this->data) {

            # Read the file data from the request. Normally, we'd just save
            # $this->data, but some table fields need to be calculated first.
           
            # Convenience variable for the Resource key of the data prop array.
            $data = $this->data['Resource'];

            # Extract some of the file info.
            $fname = $data['file']['name'];
            $tmp   = $data['file']['tmp_name'];
            $mime  = $data['file']['type'];

            # Create the resource file.
            $sha = $this->Resource->createFile($tmp, array(
                'filename' => $fname
            ));

            # If creating the file went wrong, something is probably wrong with
            # the configuration. Redirect to the status page.
            if (!$sha) {
                $this->Session->setFlash('You were redirected to this page 
                    because we were unable to save your resource. Please 
                    verify your configuration.', 'flash_error');
                return $this->redirect('/status');
            }

            # Temporarily whitelist a few fields.
            $this->Resource->permit(
                'sha', 'file_name', 'file_size', 'user_id'
            );
            # Save a DB record.
            $this->Resource->add(array(
                'sha' => $sha,
                'title' => $data['title'],
                'type' => $data['type'],
                'public' => $data['public'],
                'file_name' => $fname,
                'file_size' => $data['file']['size'],
                'mime_type' => $mime,
                'user_id' => $this->Auth->user('id')
            ));
            $id = $this->Resource->id;

            # Optionally add it to a collection.
            if ($collection_id)
                $this->Resource->Membership->pair($id, $collection_id);

            # Save any keywords.
            if ($data['keywords']) {
                $keywords = $this->Resource->Keyword->fromString($data['keywords']);
                $records = array();
                foreach ($keywords as $k) {
                    $records[] = array(
                        'resource_id' => $id,
                        'user_id' => $this->Auth->user('id'),
                        'keyword' => $k
                    );
                }
                $this->Resource->Keyword->saveMany($records);
            }

            # Make a task to get a thumbnail made, don't have time now.
            # (We need the requests to go quickly for batch uploads.)
            $this->Task->queue('thumb', $id);

            # Set a flash message, redirect to the resource view.
            $this->Session->setFlash('Resource created.', 'flash_success');
            $this->redirect(array('action' => 'view', $id));
        } else {
            $config = Configure::read('resources.types');
            $types = is_array($config) ? $config : array();
            # Prepare our options array. Keys = values.
            foreach($types as $k => $v) {
                $types[$v] = $v;
                unset($types[$k]);
            }
            $this->set('types', $types);
        }
    }

    /**
     * Creates a task to split a PDF into individual resources. Note it doesn't
     * actually do any splitting within the Request->Response loop.
     * 
     * @param id    resource id
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
                return $this->jsonResponse(202);
            }
            # Set a flash message otherwise.
            $this->Session->setFlash('Resource has been queued for splitting.',
                'flash_success');
        } else {
            if ($this->request->is('ajax')) return $this->jsonResponse(400);
            $this->Session->setFlash('Can only split a PDF file.', 'flash_error');
        }
        $this->redirect(array('action' => 'view', $id));
    }

    /**
     * Update the resource. Ajax only.
     *
     * @param id    resource id
     */
    public function update($id) {
        $resource = $this->Resource->findById($id);
        if ($this->request->is('ajax')) {
            if (!$resource) {
                return $this->jsonResponse(404);
            }
            $data = array('Resource' => $this->request->data);
            if ($this->Resource->save($data)) return $this->jsonResponse(200);
            return $this->jsonResponse(500);
        }
    }

    /**
     * Display the resource, as either HTML or JSON.
     *
     * @param id              resource id
     * @param ignore_context  if true, the action will not redirect to the
     *                        collection view when the resource has a non-null
     *                        context attribute. This is irrelevant for ajax
     *                        requests; we'll never redirect those.
     */
    public function view($id, $ignore_context=false) {
        $resource = $this->Resource->findById($id);
        $public = $resource['Resource']['public'];

        # Handle AJAX and return with a response
        if ($this->request->is('ajax')) {
            if ($resource) {
                # Resource must be public OR the user must be logged in
                if ($public || $this->Auth->loggedIn()) {
                    # Everything checks out, return the resource
                    return $this->jsonResponse(200, $resource['Resource']);
                } else {
                    # Not authorized
                    return $this->jsonResponse(403);
                }
            } else {
                # Resource doesn't exist
                return $this->jsonResponse(404);
            }
        }

        # Exists and public or authenticated.
        if ($resource && ($public || $this->Auth->loggedIn())) {

            # Redirect if the resource's context is non-null.
            if ($resource['Resource']['context'] && !$ignore_context) {
                return $this->redirect('/collection/' . 
                    $resource['Resource']['context'] . '/' . $id
                );
            }

            $memberships = $this->Resource->Membership->find('all', array(
                'conditions' => array('Membership.resource_id' => $id)
            ));

            $this->set('memberships', $memberships);
            $this->set('resource', $resource['Resource']);

            # On the first request of a particular resource (usually directly 
            # after upload), we might prompt the user for additional 
            # actions/information. Here we're turning that off for future 
            # requests. (Note that the first_req will still be true within the 
            # $resource var.)
            if ($resource['Resource']['first_req']) {
                $this->Resource->read(null, $id);
                $this->Resource->set('first_req', false);
                $this->Resource->save();
            }
        # Doesn't exist.
        } elseif (!$resource) {
            $this->redirect('/404');
        # Not authorized
        } else {
            $this->Session->setFlash("Oops. You'll need to login to view that.",
                                     'flash_error');
            $this->Session->write('redirect', '/resource/' . $id);
            $this->redirect($this->Auth->redirect('/users/login'));
        }
    }

    /**
     * Delete the resource, if authorized. Ajax only.
     *
     * @param id    resource id
     */
    public function delete($id=null) {
        if ($this->request->is('ajax')) {
            if ($this->Auth->loggedIn() && $this->Resource->delete($id)) {
                return $this->jsonResponse(200);
            } else {
                return $this->jsonResponse(401);
            }
        }
    }

    /**
     * Search resources. Results are only returned when requested via ajax.
     */
    public function search() {
        $this->set('title_for_layout', 'Search');

        if ($this->request->is('ajax')) {
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
                if (!$this->Auth->loggedIn()) $search->public = true;

                # Get the result ids.
                $ids = $search->results($limit, $offset);

                # Return the results in HABTM format.
                $this->jsonResponse(200, $this->Resource->find('all', array(
                    'conditions' => array(
                        'Resource.id' => $ids
                    ),
                    'order' => "Resource.$order DESC"
                )));

            } else {
                $conditions = $this->Auth->loggedIn() ? 
                    array() : array('Resource.public' => 1);
                # No facets provided. Give them back some random ones.
                $this->jsonResponse(200, $this->Resource->find('all', array(
                    'conditions' => $conditions,
                    'limit' => $limit,
                    'offset' => $offset,
                    'order' => "Resource.$order DESC"
                )));
            }
        } 
    }

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

    public function zipped() {
        if (!$this->request->is('ajax')) return $this->redirect('/404');
        if (!($this->request->is('post') && $this->request->data))
            return $this->jsonResponse(400);
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
        $this->jsonResponse(200, array(
            'url' => $this->Resource->url($sha, $name . '.zip')
        ));
    }

    public function rethumb($id) {
        if (!$this->request->is('ajax')) $this->redirect('/404');
        if (!$this->Auth->user('id')) return $this->jsonResponse(401);
        $resource = $this->Resource->findById($id);
        if (!$resource) return $this->jsonResponse(404);
        $this->loadModel('Task');
        $this->Task->queue('thumb', $resource['Resource']['id']);
        return $this->jsonResponse(202);
    }

    /**
     * Return associated comments.
     *
     * @param id    resource id
     */
    public function comments($id) {
        if ($this->request->is('ajax')) {
            $this->jsonResponse(200, $this->Resource->Comment->find('all',
                array('conditions' => array('Resource.id' => $id))
            ));
        }
    }

    /**
     * Return associated keywords.
     *
     * @param id    resource id
     */
    public function keywords($id) {
        if ($this->request->is('ajax')) {
            $this->jsonResponse(200, $this->Resource->Keyword->find('all',
                array('conditions' => array('Resource.id' => $id))
            ));
        }
    }

    /**
     * Return associated hotspots.
     *
     * @param id    resource id
     */
    public function hotspots($id) {
        if ($this->request->is('ajax')) {
            $this->jsonResponse(200, $this->Resource->Hotspot->find('all',
                array('conditions' => array('Resource.id' => $id))
            ));
        }
    }

    /**
     * Return a list of values for autocompletion.
     *
     * @param field
     */
    public function complete($field) {
        if ($this->request->is('ajax')) {
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
            return $this->jsonResponse(200, $values);
        }
    }
}
