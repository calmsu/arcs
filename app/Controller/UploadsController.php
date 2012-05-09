<?php
/**
 * Uploads controller.
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class UploadsController extends AppController {
    public $name = 'Uploads';
    public $uses = array('Resource');

    /**
     * Upload files without creating Resources. Responds with a JSON array that 
     * will contain the SHA1s of the created resources. This is used in tandem 
     * with `batch_upload`, which will create the resources, given SHA1s.
     */
    public function add_files() {
        $files = array();
        foreach($_FILES as $f) {
            $tmp = is_array($f['tmp_name']) ? $f['tmp_name'][0] : $f['tmp_name'];
            $name = is_array($f['name']) ? $f['name'][0] : $f['name'];
            $error = is_array($f['error']) ? $f['error'][0] : $f['error'];
            $sha = $this->Resource->createFile($tmp, array(
                'filename' => $name, 
                'thumb' => true
            ));
            $files[] = array(
                'name' => $name,
                'error' => $error,
                'sha' => $sha,
                'thumb' => $this->Resource->url($sha, 'thumb.png'),
                'preview' => is_file($this->Resource->path($sha, 'preview.png')) ?
                    $this->Resource->url($sha, 'preview.png') : 
                    $this->Resource->url($sha, $name)
            );
        }
        $this->json(201, $files);
    }

    public function basic() {
        if (!$this->request->is('post')) return;
        if (!$this->data) throw new BadRequestException();
        $data = $this->data['Resource'];
        $data['user_id'] = $this->Auth->user('id');
        $this->Resource->fromFile($data['file'], $data);
        # Save any keywords.
        if ($data['keywords'])
            $this->Resource->Keyword->saveFromString($data['keywords'], array(
                'resource_id' => $this->Resource->id,
                'user_id' => $this->Auth->user('id')
            ));
        # Set a flash message, redirect to the viewer.
        $this->Session->setFlash('Resource created.', 'flash_success');
        $this->redirect('/resource/' . $this->Resource->id);
    }

    /**
     * Display the batch uploader on GET, and accept uploads on POST.
     */
    public function batch() {
        if (!$this->request->is('post')) return;
        if (!$this->request->data) throw new BadRequestException();
        foreach($this->request->data as $upload) {
            # Temporarily whitelist a few fields.
            $this->Resource->permit('sha', 'file_name', 'file_size', 'user_id');
            $this->Resource->add(array(
                'sha'       => $upload['sha'],
                'file_name' => $upload['name'],
                'file_size' => $upload['size'],
                'title'     => $upload['title'],
                'mime_type' => $upload['type'],
                'type'      => $upload['rtype'],
                'user_id'   => $this->Auth->user('id'),
                'public'    => false
            ));
            if ($upload['identifier']) {
                $this->Resource->Metadatum->store(
                    $this->Resource->id, 'identifier', $upload['identifier']
                );
                $this->Resource->Metadatum->create();
            }
            $this->Resource->create();
        }
        return $this->json(201);
    }
}
