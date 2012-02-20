<?php

require_once(APPLIBS . 'Mime.php');

class Resource extends AppModel {
    public $name = 'Resource';
    public $belongsTo = 'User';
    public $hasMany = array(
        'Membership',
        'Comment',
        'Tag',
        'Hotspot'
    );

    /**
     * Adds the URLs to the Resource's file and thumbnail to the return array.
     * Note that we don't store those, so we must dynamically generate them.
     */
    public function afterFind($results, $primary) {
        if (!$primary) {
            if (isset($results['sha'])) {
                $sha = $results['sha'];
                $name = $results['file_name'];
                $results['url'] = $this->url($sha) . DS . $name;
                $results['thumb'] = $this->url($sha) . DS . 'thumb.png';
            }
            return $results;
        } else if (isset($results[0]['Resource']['sha'])) { 
            foreach($results as $k=>$v) {
                $sha = $results[$k]['Resource']['sha'];
                $name = $results[$k]['Resource']['file_name'];
                $results[$k]['Resource']['url'] = $this->url($sha) . DS . $name;
                $results[$k]['Resource']['thumb'] = $this->url($sha) . DS .'thumb.png';
            }
            return $results;
        } else {
            return $results;
        }
    }

    /**
     * Creates the file-level components of a Resource.
     *
     * Given a file path, it will calculate a SHA1 and build a path
     * in the configured uploads directory and
     *
     * @param src    path to a readable file.
     * @param fname  provide the desired filename, if different than 
     *               src path basename.
     * @param move   if false, copy the file rather than move it. Will
     *               move by default.
     * @return       a SHA1 hexdigest that can be used to get the 
     *               resource's path.
     */
    public function create($src, $fname=null, $move=true) {

        # If we can't read the src path, return false.
        if (!is_readable($src)) {
            return false;
        }

        # Get the SHA, destination path, and filename.
        $sha = $this->_getSHA($src);
        $fname = is_null($fname) ? basename($src) : $fname;
        $dst = $this->path($sha);

        # Try to make the new directory.
        if (!mkdir($dst, 0777, true)) {
            return false;
        }

        # Try to move if move and writable.
        if ($move && is_writable($src)) {
            $success = rename($src, $dst . DS . $fname);
        # Otherwise try to copy.
        } else {
            $success = copy($src, $dst . DS . $fname);
        }
        # Return false on failure.
        if (!$success) {
            return false;
        }

        # Copy over a default thumbnail.
        $this->_setDefaultThumb($dst);

        return $sha;
    }

    /**
     * Return the file path to the resource.
     *
     * This is based on the paths.uploads setting in `arcs.ini`
     *
     * @param sha    resource's SHA1
     */
    public function path($sha) {
        return $this->_path($sha, Configure::read('paths.uploads'), DS);
    }

    /**
     * Return the url to the resource.
     *
     * This is based on the urls.uploads setting in `arcs.ini`
     *
     * @param sha   resource's SHA1
     */
    public function url($sha) {
        return $this->_path($sha, Configure::read('urls.uploads'));
    }

    /* PRIVATE METHODS */

    /**
     * Builds paths for resources using the ARCS spec. For example:
     *
     * $this->_path('123456789abcdef...', '/root/dir/')
     *
     * # '/root/dir/1/2/3/456789abcdef...'
     *
     * @param sha   resource's SHA1
     * @param root  root path to prepend
     * @param sep   separator to use, in most cases '/' or '\'
     * @return      full path
     */
    private function _path($sha, $root='', $sep='/') {
        # Trim any trailing sep
        $root = rtrim($root, $sep);
        return $root . $sep . 
            substr($sha, 0, 1) . $sep . 
            substr($sha, 1, 1) . $sep . 
            substr($sha, 2, 1) . $sep . 
            substr($sha, 3);
    }

    /**
     * Computes a SHA1 given the file name, using the time, and the application
     * security salt.  The SHA is used to generate the resource file path.
     *
     * @param name   file name, or any suitable string
     * @return       a SHA1 hexdigest
     */
    private function _getSHA($name) {
        $salt = Configure::read('Security.salt');
        return sha1($name . time() . $salt);
    }

    /**
     * Puts a default thumbnail within the path, given a MIME type.
     *
     * We don't create thumbnails during the Request-Response loop, but 
     * we'll copy over a placeholder.
     *
     * @param mime   resource's MIME type (for example 'image/gif')
     * @param path   result of `path` method called with the resource's sha
     */
    private function _setDefaultThumb($path) {
        $type = Mime::getExt($path);
        if ($type) {
            $src = $type . '.png';
        } else {
            $src = 'generic.png';
        }
        copy(IMAGES . 'default_thumbs' . DS . $src, $path . DS . 'thumb.png');
    }
}
