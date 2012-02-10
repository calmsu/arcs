<?php
class Comment extends AppModel {
    public $name = 'Comment';
    public $belongsTo = array('User', 'Resource');
}
