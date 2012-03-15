<?php
class Flag extends AppModel {
    public $name = 'Flag';
    public $belongsTo = array('User', 'Resource');
}
