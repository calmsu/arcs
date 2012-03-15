<?php
class Tag extends AppModel {
    public $name = 'Tag';
    public $belongsTo = array('User', 'Resource');
    public $whitelist = array('tag', 'resource_id');

    public function fromString($string) {
        $tags = array();
        foreach(explode(',', $string) as $t) {
            $t = trim($t);
            if (strlen($t))
                $tags[] = $t;
        }
        return $tags;
    }
}
