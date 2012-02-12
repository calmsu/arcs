<?php

class Resource extends AppModel {
    public $name = 'Resource';
    public $belongsTo = 'User';
    public $hasMany = array(
        'Membership',
        'Comment',
        'Tag',
        'Hotspot'
    );

    /* MIME-type translation arrays */

    public $imageMimes = array(
        'image/png' => 'png',
        'image/jpeg' => 'jpeg',
        'image/jpg' => 'jpg',
        'image/gif' => 'gif',
    );

    public $documentMimes = array(
        'application/pdf' => 'pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/msword' => 'doc',
        'text/plain' => 'plaintext',
        'text/richtext' => 'richtext',
        'text/rtf' => 'rtf'
    );

    public $videoMimes = array(
        'video/mpeg' => 'mpeg',
        'video/msvideo' => 'avi',
        'video/quicktime' => 'mov'
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
                $results['url'] = $this->getURL($sha) . '/' . $name;
                $results['thumb'] = $this->getURL($sha) . '/thumb.png';
            }
            return $results;
        } else if (isset($results[0]['Resource']['sha'])) { 
            foreach($results as $k=>$v) {
                $sha = $results[$k]['Resource']['sha'];
                $name = $results[$k]['Resource']['file_name'];
                $results[$k]['Resource']['url'] = $this->getURL($sha) . '/' . $name;
                $results[$k]['Resource']['thumb'] = $this->getURL($sha) . '/thumb.png';
            }
            return $results;
        } else {
            return $results;
        }
    }

    /**
     * Determines if the file with the given MIME type is a document (with 
     * reasonable assurance). A document is defined as anything with a .pdf, 
     * .doc, or .docx file extension (but of course we don't trust those, so 
     * we're checking the MIME type).
     *
     * @param mime    a valid MIME type
     * @returns       true if it might be a document, false otherwise.
     */
    public function isDoc($mime) {
        $documents = array_keys($this->documentMimes);
        if (in_array($mime, $documents)) {
            return true;
        }
        return false;
    }

    /**
     * Computes a SHA1 given the file name, using the time, and the application
     * security salt for use in generating the resource file path.
     *
     * @param name   file name, or any suitable string
     * @return       a SHA1 hexdigest
     */
    public function getSHA($name) {
        $salt = Configure::read('Security.salt');
        return sha1($name . time() . $salt);
    }

    /**
     * Computes (and if necessary, builds) the resource path, given its SHA1.
     * It uses the `filestore_path` defined in arcs.ini.
     *
     * @param sha    resource SHA1
     * @param make   if true, create directories above the path, if permitted.
     * @return       resource path 
     */
    public function getPath($sha, $make=false) {
        # Get the filestore setting
        $root = realpath(Configure::read('paths.uploads'));
        # Resource paths are relative to the filestore, and are stored 3
        # directories deep, using the first 3 digits of the sha.
        $path = $root . DS . substr($sha, 0, 1) . DS . substr($sha, 1, 1) . 
                DS . substr($sha, 2, 1) . DS . substr($sha, 3);
        # Make the path if desired and non-existent
        if ($make) {
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }
        return $path;
    }

    /**
     * Constructs a URL relative to the configured 'filestore_url', given the
     * Resource's SHA. 
     *
     * @param   resource's SHA1
     */
    public function getURL($sha) {
        $root = Configure::read('urls.uploads');
        $root = rtrim($root, '/');
        return sprintf("%s/%s/%s/%s/%s", $root, substr($sha, 0, 1), 
                       substr($sha, 1, 1), substr($sha, 2, 1), substr($sha, 3));
    }

    /**
     * Copies over the default thumbnail for the mime-type, while the real one
     * is being created.
     *
     * @param mime   resource's MIME type (for example 'image/gif')
     * @param path   result of getPath() called with the resource's sha
     */
    public function setDefaultThumb($mime, $path) {
        $types = array_merge($this->documentMimes, $this->imageMimes, 
                             $this->videoMimes);
        if (array_key_exists($mime, $types)) {
            $src = $types[$mime] . '.png';
        } else {
            $src = 'generic.png';
        }
        copy(IMAGES . 'default_thumbs' . DS . $src, $path . DS . 'thumb.png');
    }

    /**
     * Return a JOIN statement relative to Resource, given parameters.
     *
     * @param table   result of $dbo->fullTableName($this->Model), with 
     *                quotes.
     * @param alias   the alias to use, most likely the model name, 
     *                unquoted.
     * @param rcol    resource column to join on
     * @param fcol    foreign column to join on
     */
    public function makeInnerJoin($table, $alias, $rcol, $fcol) {
        return "INNER JOIN {$table} `{$alias}` " .
            "ON `Resource`.`{$rcol}` = `{$alias}`.`{$fcol}` ";
    }

}
