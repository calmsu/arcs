<?php
class Hotspot extends AppModel {
    public $name = 'Hotspot';
    public $belongsTo = array('Resource', 'User');
}
