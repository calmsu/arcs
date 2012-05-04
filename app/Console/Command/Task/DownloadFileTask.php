<?php

include_once(LIB . 'Relic' . DS . 'Library' . DS . 'Mime.php');

class DownloadFileTask extends AppShell {

    public $uses = array('Resource');

    public function execute($data) {
        $this->_loadModels();
        $tmp = tempnam(sys_get_temp_dir(), 'ARCS');
        $url = $data['url'];
        if (!preg_match('/^(http|https|ftp)/', $url))
            throw new Exception('Not a valid URL');
        if (!copy($data['url'], $tmp)) 
            throw new Exception('Could not read URL');
        $this->Resource->fromFile(array(
            'tmp_name' => $tmp,
            'name' => basename($url),
            'size' => filesize($tmp),
            'type' => \Relic\Mime::mime($tmp)
        ), $data);
    }
}
