<?php
class ZipTask extends AppShell {
    public $uses = array('Resource');

    public function execute($data) {
        $resources = $this->Resource->find('all', array(
            'conditions' => array(
                'Resource.id' => $data['resources']
            )
        ));
        $files = array();
        foreach ($resources as $r) {
            $files[$r['Resource']['file_name']] = $r['Resource']['sha'];
        }
        $this->out($this->Resource->makeZipfile($files));
    }
}
