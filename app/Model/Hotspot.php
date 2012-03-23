<?php
class Hotspot extends AppModel {
    public $name = 'Hotspot';
    public $belongsTo = array('Resource', 'User');
    public $whitelist = array(
        'resource_id', 'title', 'caption', 'link', 'x1', 'y1', 'x2', 'y2'
    );
}
