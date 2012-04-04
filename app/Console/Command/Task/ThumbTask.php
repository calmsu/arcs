<?php
class ThumbTask extends AppShell {
    public $uses = array('Resource');

    public function execute($data) {
        $this->_loadModels();
        $resource = $this->Resource->findById($data['resource_id']);
        return $this->Resource->makeThumbnail($resource['Resource']['sha']);
    }
}
