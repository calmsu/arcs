<?php
/**
 * Uploads controller.
 *
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class UploadsController extends AppController {
    public $name = 'Uploads';
    public $uses = array('Resource', 'Task');

    /**
     * Upload files without creating Resources. Responds with a JSON
     * array that will contain the SHA1s of the created resources. This
     * is used in tandem with `batch_upload`, which will create the
     * resources, given SHA1s.
     */
    public function add_files() {
        $files = array();
        foreach($_FILES as $f) {
            $tmp = is_array($f['tmp_name']) ? $f['tmp_name'][0] : $f['tmp_name'];
            $name = is_array($f['name']) ? $f['name'][0] : $f['name'];
            $error = is_array($f['error']) ? $f['error'][0] : $f['error'];
            $sha = $this->Resource->createFile($tmp, $name, true, true);
            $files[] = array(
                'name' => $name,
                'error' => $error,
                'sha' => $sha,
                'thumb' => $this->Resource->url($sha, 'thumb.png')
            );
        }
        $this->jsonResponse(200, $files);
    }

    public function standard() {
    }

    public function batch() {
        if ($this->request->is('ajax') && $this->request->data) {
            foreach($this->request->data as $upload) {
                # Temporarily whitelist a few fields.
                $this->Resource->permit(
                    'sha', 'file_name', 'file_size', 'user_id'
                );
                $this->Resource->add(array(
                    'sha' => $upload['sha'],
                    'file_name' => $upload['name'],
                    'file_size' => $upload['size'],
                    'title'     => $upload['title'],
                    'mime_type' => $upload['type'],
                    'user_id'   => $this->Auth->user('id'),
                    'public'    => false
                ));
                $this->Resource->create();
                $this->Task->create();
            }
            return $this->jsonResponse(201);
        }
    }
}
