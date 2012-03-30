<?php

class Collection extends AppModel {
    public $name = 'Collection';
    public $hasMany = array('Membership');
    public $whitelist = array(
        'title', 'description', 'public', 'pdf', 'temporary'
    ); 
}
