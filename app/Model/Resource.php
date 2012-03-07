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
                $results['url'] = $this->url($sha, $name);
                $results['thumb'] = $this->url($sha, 'thumb.png');
            }
            return $results;
        } else if (isset($results[0]['Resource']['sha'])) { 
            foreach($results as $k=>$v) {
                $sha = $results[$k]['Resource']['sha'];
                $name = $results[$k]['Resource']['file_name'];
                $results[$k]['Resource']['url'] = $this->url($sha, $name);
                $results[$k]['Resource']['thumb'] = $this->url($sha, 'thumb.png');
            }
            return $results;
        } else {
            return $results;
        }
    }

    /**
     * Creates the file-level components of a Resource.
     *
     * Given a file path, it will calculate a SHA1 checksum of it, then build a
     * path for the file from the hexdigest and put it there. 
     *
     * @param src    path to a readable file.
     * @param fname  provide the desired filename, if different than 
     *               src path basename.
     * @param move   if false, copy the file rather than move it. Will
     *               move by default.
     * @return       a SHA1 hexdigest that can be used to get the 
     *               resource's path.
     */
    public function createFile($src, $fname=null, $move=true) {

        # If we can't read the src path, return false.
        if (!is_readable($src)) return false;

        # Get the SHA, destination path, and filename.
        $sha = $this->_getSHA($src);
        $fname = is_null($fname) ? basename($src) : $fname;
        $dst = $this->path($sha);

        # Try to make the new directory if it's not already there.
        if (!is_dir($dst))
            # Return false if we can't make it.
            if (!mkdir($dst, 0777, true)) return false;

        # If the file doesn't already exist (it may be a duplicate):
        if (!is_file($dst . DS . $sha)) {
            # Try to move if move and writable.
            if ($move && is_writable($src)) {
                $success = rename($src, $dst . DS . $sha);
            # Otherwise try to copy. (Maybe it's read-only)
            } else {
                $success = copy($src, $dst . DS . $sha);
            }
            # Return false on failure.
            if (!$success) return false;
            # Copy over a default thumbnail.
            $this->_setDefaultThumb($dst);
        }

        # Create a hard link to the file, if the file doesn't already exist.
        if (!is_file($dst . DS . $fname))
            # Return false if we can't make the link.
            if (!link($dst . DS . $sha, $dst . DS . $fname)) return false;

        # Return the hexdigest.
        return $sha;
    }

    /**
     * Return the file path to the resource's directory.
     *
     * This is based on the paths.uploads setting in `arcs.ini`
     *
     * @param sha    resource's SHA1
     * @param fname  filename
     */
    public function path($sha, $fname=null) {
        $path = $this->_path($sha, Configure::read('paths.uploads'), DS);
        if ($fname) return $path . DS . $fname;
        return $path;
    }

    /**
     * Return the url to the resource's directory.
     *
     * This is based on the urls.uploads setting in `arcs.ini`
     *
     * @param sha   resource's SHA1
     * @param fname resource's filename
     */
    public function url($sha, $fname=null) {
        $url = $this->_path($sha, Configure::read('urls.uploads'));
        if ($fname) return $url . DS . $fname;
        return $url;
    }

    /**
     * Return the file size of a Resource, in bytes.
     *
     * @param sha   resource's SHA1
     * @param fname resource's filename
     */
    public function size($sha, $fname) {
        return filesize($this->path($sha) . DS . $fname);
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
     * Computes a SHA1 checksum of the file at the given path.
     *
     * @param name   file path
     * @return       a SHA1 hexdigest
     */
    private function _getSHA($path) {
        return sha1_file($path);
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
        $src = $type ? $type . '.png' : 'generic.png';
        copy(
            IMAGES . 'default_thumbs' . DS . $src, 
            $path . DS . 'thumb.png'
        );
    }
}
