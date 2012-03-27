<?php
class Comment extends AppModel {
    public $name = 'Comment';
    public $belongsTo = array('User', 'Resource');
    public $whitelist = array('resource_id', 'content');
}
