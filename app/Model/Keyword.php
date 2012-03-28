<?php
class Keyword extends AppModel {
    public $name = 'Keyword';
    public $belongsTo = array('User', 'Resource');
    public $whitelist = array('keyword', 'resource_id');

    public function saveFromString($string, $data=array()) {
        $keywords = array();
        foreach(explode(',', $string) as $k) {
            $keyword = $data;
            $keyword['keyword'] = trim($k);
            if (strlen($k)) $keywords[] = $keyword;
        }
        return $this->saveMany($keywords);
    }
}
