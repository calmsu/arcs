<?php
class Keyword extends AppModel {
    public $name = 'Keyword';
    public $belongsTo = array('User', 'Resource');
    public $whitelist = array('keyword', 'resource_id');

    public function fromString($string) {
        $keywords = array();
        foreach(explode(',', $string) as $k) {
            $k = trim($k);
            if (strlen($k))
                $keywords[] = $k;
        }
        return $keywords;
    }
}
