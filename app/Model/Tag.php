<?php
class Tag extends AppModel {
    public $name = 'Tag';
    public $belongsTo = array('User', 'Resource');
}
