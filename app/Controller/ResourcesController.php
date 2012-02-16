<?php
App::uses('Sanitize', 'Utility');
/**
 * Resources Controller
 *
 * Logic for retrieving and presenting resources, largely via ajax.
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class ResourcesController extends AppController {
    public $name = 'Resources';

    public function beforeFilter() {
        # The App Controller will set some common view variables (namely a 
        # user array), so the parent's beforeFilter is run in this and most 
        # other controllers.
        parent::beforeFilter();

        # Read-only actions, such as viewing resources and associated comments
        # are allowed by default.
        $this->Auth->allow(
            'index', 'view', 'search', 'comments', 'hotspots', 'tags'
        );
        $this->RequestHandler->addInputType('json', array('json_decode', true));
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
            $name = $this->data['Resource']['file']['name'];
            $tmp  = $this->data['Resource']['file']['tmp_name'];
            $mime = $this->data['Resource']['file']['type'];
            $sha  = $this->Resource->getSHA($name);
            $path = $this->Resource->getPath($sha, true);
            if (!$path) {
                $this->Session->setFlash('You were redirected to this page because we
                    were unable to save your resource. Please verify your
                    configuration.', 'flash_error');
                return $this->redirect('/status');
            }
            $this->Resource->setDefaultThumb($mime, $path);

            # Move the file from tmp
            rename($tmp, $path . DS . $name);

            # Save a DB record.
            $this->Resource->save(array(
                'Resource' => array(
                    'sha' => $sha,
                    'title' => $this->data['Resource']['title'],
                    'type' => $this->data['Resource']['type'],
                    'public' => $this->data['Resource']['public'],
                    'file_name' => $name,
                    'mime_type' => $mime,
                    'user_id' => $this->Auth->user('id')
            )));

            # Optionally add it to a collection.
            if ($collection_id) {
                $this->Resource->Membership->save(array(
                    'Membership' => array(
                        'resource_id' => $this->Resource->id,
                        'collection_id' => $collection_id
                    )
                ));
            }

            # Save any tags.
            if (strlen($this->data['Resource']['tags'])) {
                # Remove whitespace
                $tags = rtrim($this->data['Resource']['tags']);
                $tagRecords = array();
                foreach(explode(',', $tags) as $t) {
                    if (strlen($t)) {
                        array_push($tagRecords, array(
                            'Tag' => array(
                                'resource_id' => $this->Resource->id,
                                'tag' => $t
                            )
                        ));
                    }
                }
                # If not empty:
                if ($tagRecords) {
                    $this->Resource->Tag->saveMany($tagRecords);
                }
            }

            # Make a task to get a thumbnail made, don't have time now.
            # (We need the requests to go quickly for batch uploads.)
            $this->loadModel('Task');
            $this->Task->save(array(
                'Task' => array(
                    'resource_id' => $this->Resource->id,
                    'job' => 'thumb',
                    'status' => 1,
                    'progress' => 0
            )));

            # Set a flash message, redirect to the resource view.
            $this->Session->setFlash('Resource created.', 'flash_success');
            $this->redirect(array('action' => 'view', $this->Resource->id));
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
    public function pdfSplit($id) {
        $resource = $this->Resource->findById($id);
        if ($resource['Resource']['mime_type'] == 'application/pdf') {
            # Create a new collection for the split.
            $this->loadModel('Collection');
            $this->Collection->save(array(
                'Collection' => array(
                    'title' => $resource['Resource']['title'],
                    'description' => 'PDF split of ' . $resource['Resource']['title'],
                    'public' => $resource['Resource']['public'],
                    'user_id' => $this->Auth->user('id'),
                    'pdf' => $id
            )));

            # Make a new task to split the PDF.
            $this->loadModel('Task');
            $this->Task->save(array(
                'Task' => array(
                    'resource_id' => $id,
                    'job' => 'pdf_split',
                    'status' => 1,
                    'data' => $this->Collection->id,
                    'progress' => 0
            )));

            # If request is ajax, return a response here.
            if ($this->request->is('ajax')) {
                # Return 202 (meaning accepted, but not yet done).
                return $this->jsonResponse(202);
            }
            # Set a flash message otherwise.
            $this->Session->setFlash('Resource has been queued for splitting.',
                'flash_success');
        } else {
            if ($this->request->is('ajax')) {
                # Bad Request
                return $this->jsonResponse(400);
            } 
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
            if ($this->Resource->save($data)) {
                return $this->jsonResponse(200);
            } else {
                return $this->jsonResponse(500);
            }
        }
    }

    /**
     * Display the resource, as either HTML or JSON.
     *
     * @param id    resource id
     */
    public function view($id) {
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
                    return $this->jsonResponse(401);
                }
            } else {
                # Resource doesn't exist
                return $this->jsonResponse(404);
            }
        }

        # Exists and public or authenticated.
        if ($resource && ($public || $this->Auth->loggedIn())) {

            $memberships = $this->Resource->Membership->find('all', array(
                'conditions' => array('Membership.resource_id' => $id)
            ));

            $this->set('memberships', $memberships);
            $this->set('resource', $resource['Resource']);

            # On the first request of a particualar resource (usually directly 
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
            }
        }
    }

    /**
     * Search resources. Results are only returned when requested via ajax.
     */
    public function search() {
        $this->set('title_for_layout', 'Search');

        if ($this->request->is('ajax')) {
            if ($this->request->data) {
                $params = $this->request->query;
                $this->jsonResponse(200, $this->facetedSearch(
                    $this->request->data,
                    isset($params['n']) ? $params['n'] : 30,
                    isset($params['offset']) ? $params['offset'] : 0
                ));
            } else {
                # No facets provided. Give them back some random ones.
                $this->jsonResponse(200, $this->Resource->find('all', array(
                    'limit' => 30,
                    # Random order, to keep it interesting:
                    'order' => array('rand()')
                )));
            }
        } 
    }

    /**
     * Does the heavy lifting for the search action.
     *
     * @param facets     numerically indexed array containing sub-arrays
     *                   with 'category' and 'value' keys. An example facet 
     *                   array is below:
     *
     *                       array(
     *                           0 => array(
     *                               'category' => 'user',
     *                               'value' => 'Nick Reynolds'
     *                           ),
     *                           1 => array(
     *                               'category' => 'tag',
     *                               'value' => 'East-Field'
     *                           )
     *                       )
     *
     * @param n          number of results that should be returned. Defaults to
     *                   30.
     *
     * @param offset     results are start at this index. (i.e. An offset of
     *                   40, with an n of 30, will get you results 40-69.) 
     *                   Defaults to 0.
     *
     * @return           array of resources meeting criteria.
     */
    private function facetedSearch($facets, $n=30, $offset=0) {
        # TODO: Move this somewhere more appropriate.
        #
        # Given the facets array described above, we must algorithmically
        # generate an SQL query that returns Resources that meet all facets.
        # To make things more interesting, not all facets occupy the resources
        # table, so joins must be done as (and only when) needed.
        #
        # We're using CakePHP's DboSource object to build most of our query.

        # Facet categories must be mapped to a model, field, and an array
        # containing join instructions. If it seems awkward, it's only because
        # it is.
        $facetMappings = array(
            'tag' => array(
                # The model that tag is associated with.
                'model' => 'Tag',
                # The model field that value will be compared against.
                'field' => 'tag',
                # The fields to join on, starting with Resource, so:
                # JOIN ON Resource.id = Tag.resource_id
                'join' => array('id', 'resource_id')
            ),
            'user' => array(
                'model' => 'User',
                'field' => 'name',
                'join' => array('user_id', 'id')
            ),
            'modified' => array(
                'model' => 'Resource',
                'field' => 'modified',
                # If the 'date' key is set and truthy, we'll wrap the comparison
                # values with the DATE() function.
                'date' => true
            ),
            'created' => array(
                'model' => 'Resource',
                'field' => 'created',
                'date' => true
            ),
            'uploaded' => array(
                'model' => 'Resource',
                'field' => 'created',
                'date' => true
            ),
            'sha' => array(
                'model' => 'Resource',
                'field' => 'sha'
            ),
            'title' => array(
                'model' => 'Resource',
                'field' => 'title'
            ),
            'id' => array(
                'model' => 'Resource',
                'field' => 'id'
            ),
            'type' => array(
                'model' => 'Resource',
                'field' => 'type'
            ),
            'filetype' => array(
                'model' => 'Resource',
                'field' => 'mime_type'
            ),
            'filename' => array(
                'model' => 'Resource',
                'field' => 'file_name'
            )
        );

        # Get our DboSource object.
        $dbo = $this->Resource->getDataSource();

        # Homes for the joins and conditions we'll shortly make:
        $joins = array();
        $joined = array();
        $conditions = array();

        # For every facet we've been given:
        foreach($facets as $f) {
            # If we don't have a mapping for it, skip.
            if (!array_key_exists($f['category'], $facetMappings)) {
                continue;
            }

            $map = $facetMappings[$f['category']];
            $model = $map['model'];
            $field = $map['field'];

            # Clean the value. This is the only user-defined string.
            $value = Sanitize::clean($f['value'], 'default');

            # Load the model.
            $this->loadModel($model);

            # Get the table name, unless it's Resource, in which case we'll
            # use the alias.
            $table = $model == 'Resource' ? $model : $dbo->fullTableName($this->$model);

            # If there are join instructions, do the join (unless we've already
            # joined this table).
            if (isset($map['join']) && $map['join'] && 
                !in_array($model, $joined)) {

                array_push($joins, $this->Resource->makeInnerJoin(
                    $table,
                    $model,
                    $map['join'][0],
                    $map['join'][1]
                ));
                array_push($joined, $model);
            }

            # Add the condition.
            #
            # To compare dates we'll need to handle them specially.
            if (isset($map['date']) && $map['date']) {
                $date = DateTime::createFromFormat('m-d-Y', $value);
                $date = $date->format('Y-m-d');
                array_push($conditions,
                    "DATE($model.$field) = DATE('$date')"
                );
            } else {
                $conditions["$model.$field"] = $value;
            }
        }

        # Build the query.
        $query = $dbo->buildStatement(
            array(
                'fields' => array('`Resource`.`id`'),
                'alias' => 'Resource',
                'limit' => $n,
                'offset' => $offset,
                'conditions' => $conditions, 
                'table' => $dbo->fullTableName($this->Resource),
                'joins' => $joins,
                'order' => null,
                'group' => null
            ), $this->Resource
        );

        $this->log($query);

        # Run the query and get the ids.
        $ids = array_map(function ($r) { return $r['Resource']['id']; }, 
            $this->Resource->query($query)
        );

        if (!$ids) {
            $this->log($query);
            return array();
        }

        # Get everything.
        return $this->Resource->find('all', array(
            'conditions' => array(
                'Resource.id' => $ids
        )));
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
     * Return associated tags.
     *
     * @param id    resource id
     */
    public function tags($id) {
        if ($this->request->is('ajax')) {
            $this->jsonResponse(200, $this->Resource->Tag->find('all',
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
     * Return a list of resource titles for autocompletion.
     */
    public function complete($field) {
        if ($this->request->is('ajax')) {
            switch($field) {
                case 'title':
                    $this->jsonResponse(200, $this->Resource->find('list', array(
                            'fields' => array('Resource.title'),
                            'limit' => 100
                        )));
                    break;
                case 'type':
                    $this->jsonResponse(200, Configure::read('resources.types'));
                    break;
                case 'created':
                    $this->jsonResponse(200, $this->Resource->find('list', array(
                            'fields' => array('Resource.created'),
                            'limit' => 100
                        )));
                    break;
                case 'modified':
                    $this->jsonResponse(200, $this->Resource->find('list', array(
                            'fields' => array('Resource.modified'),
                            'limit' => 100
                        )));
                    break;
            }
        }
    }
}
